<?php

namespace App\Http\Controllers;

use App\Models\GitHubConnection;
use App\Models\Landlord\Tenant;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\GitHubService;
use App\Services\SpaceService;
use App\Support\RequestContextResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Native\Laravel\Facades\Settings;

class GitHubOAuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Redirect to GitHub OAuth for authentication or account linking
     */
    public function redirect(): RedirectResponse
    {
        $space = request()->query('space');
        $validator = \Validator::make(
            ['space' => $space],
            ['space' => 'required|alpha_dash|max:50']
        );
        $validator->validate();

        session(['space' => $space]);

        // No authentication check - supports both login and linking
        // Scopes can be configured in config/services.php
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle GitHub OAuth callback - routes to authentication or linking
     */
    public function callback(Request $request)
    {
        try {
            // retrieve space from session and set tenant context FIRST
            $space = session('space');
            if (!$space) {
                return redirect('/login')
                    ->with('error', 'Organization context is missing. Please try logging in again.');
            }

            // Use landlord connection explicitly to find tenant
            $tenant = Tenant::on('landlord')->where('host', $space)->first();
            if (!$tenant) {
                return redirect('/login')
                    ->with('error', 'The specified organization does not exist.');
            }

            $tenant->makeCurrent();

            $githubUser = Socialite::driver('github')->user();
            
            // @phpstan-ignore-next-line
            $token = $githubUser->token ?? '';

            \Log::debug('GitHub OAuth callback', [
                'github_user_id' => $githubUser->getId(),
                'github_username' => $githubUser->getNickname(),
                'tenant_id' => $tenant->id,
            ]);


            // Check if user is authenticated - determines flow
            if (auth('tenant')->check()) {
                return $this->handleAccountLinking(auth('tenant')->user(), $githubUser, $token);
            }

            return $this->handleAuthentication($githubUser, $token);

        } catch (InvalidStateException $e) {
            \Log::warning('GitHub OAuth callback invalid state', [
                'error' => $e->getMessage(),
            ]);
            if (auth('tenant')->check()) {

                return redirect($this->settingsRoute())
                    ->with('error', 'Invalid OAuth state. Please try again.');
            }

            return redirect('/')
                ->with('error', 'Invalid OAuth state. Please try again.');
        } catch (\Exception $e) {
            if (auth('tenant')->check()) {
                \Log::error('GitHub OAuth callback error for authenticated user', [
                    'user_id' => auth('tenant')->id(),
                ]);
                \Log::error($e->getMessage());
                return redirect($this->settingsRoute())
                    ->with('error', 'An error occurred during GitHub authentication. ' . $e->getMessage());
            }

            return redirect('/login')
                ->with('error', 'An error occurred during GitHub authentication. ' . $e->getMessage());
        }
    }

    /**
     * Handle authentication flow (login/registration)
     */
    private function handleAuthentication($githubUser, string $token): RedirectResponse
    {
        // Tenant context already set in callback() method
        $tenant = Tenant::current();
        if (!$tenant) {
            return redirect('/login')
                ->with('error', 'Organization context is missing. Please try logging in again.');
        }

        // Lookup user by GitHub ID or email
        $user = $this->userRepository->findByGitHubId($githubUser->getId())
            ?? $this->userRepository->findByEmail($githubUser->getEmail());

        // Create user if doesn't exist (web mode only)
        if (!$user) {
            if (RequestContextResolver::isDesktop()) {
                \Log::debug('GitHub user not found in desktop mode', [
                    'github_user_id' => $githubUser->getId(),
                ]);
                return redirect('/login')
                    ->with('error', 'User not found. Please sign up via web first.');
            }

            // Validate email is present
            if (!$githubUser->getEmail()) {
                return redirect('/login')
                    ->with('error', 'Please make your GitHub email public to continue.');
            }

            $user = User::create([
                'first_name' => $this->extractFirstName($githubUser->getName()),
                'last_name' => $this->extractLastName($githubUser->getName()),
                'email' => $githubUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]);
        }

        // Create or update GitHub connection
        $this->createOrUpdateConnection($user, $githubUser, $token);

        // Login user
        Auth::guard('tenant')->login($user);

        // Desktop mode: store in settings (if NativePHP is available)
        if (RequestContextResolver::isDesktop()) {
            try {
                if (class_exists(Settings::class)) {
                    Settings::set('authenticated_user', $user->toArray());
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to save user to desktop settings', ['error' => $e->getMessage()]);
            }
        }

        // Redirect based on tenant context
        $tenant = Tenant::current();

        if ($tenant) {
            // On tenant subdomain - redirect to dashboard
            $params = [];
            if (app()->environment('staging') || app()->isProduction()) {
                $params['account'] = $tenant->host;
            }

            SpaceService::registerSpaceCookie($tenant->host);

            return redirect()->route('dashboard', $params);
        }

        // No tenant context - user needs to select/create organization
        // Show landing page with success message
        return redirect('/')
            ->with('success', 'Successfully logged in with GitHub! Please select or create your organization.');
    }

    /**
     * Handle account linking flow (for already authenticated users)
     */
    private function handleAccountLinking(User $currentUser, $githubUser, string $token): RedirectResponse
    {
        // Check if this GitHub account is already linked to another user
        $existingConnection = GitHubConnection::where('github_user_id', $githubUser->getId())
            ->where('user_id', '!=', $currentUser->id)
            ->first();

        if ($existingConnection) {
            return redirect()->route('settings.integrations')
                ->with('error', 'This GitHub account is already linked to another user.');
        }

        // Check if current user already has a GitHub connection
        $currentConnection = GitHubConnection::where('user_id', $currentUser->id)->first();

        if ($currentConnection && $currentConnection->github_user_id != $githubUser->getId()) {
            // User is trying to link a different GitHub account
            return redirect()->route('settings.integrations')
                ->with('confirm_replace', [
                    'message' => 'You already have a GitHub account linked. Do you want to replace it?',
                    'current_username' => $currentConnection->username,
                    'new_username' => $githubUser->getNickname(),
                    'temp_data' => encrypt([
                        'github_user_id' => $githubUser->getId(),
                        'username' => $githubUser->getNickname(),
                        'access_token' => $token,
                        'refresh_token' => $githubUser->refreshToken ?? null,
                        'expires_in' => $githubUser->expiresIn ?? null,
                    ]),
                ]);
        }

        // Create or update the connection
        $connection = $this->createOrUpdateConnection($currentUser, $githubUser, $token);

        // Test the connection
        $service = new GitHubService($connection);
        if (!$service->testConnection()) {
            $connection->delete();

            return redirect()->route('settings.integrations')
                ->with('error', 'Failed to establish GitHub connection. Please try again.');
        }

        return redirect()->route('settings.integrations')
            ->with('success', "GitHub account @{$githubUser->getNickname()} linked successfully!");
    }

    /**
     * Confirm replacement of existing GitHub connection
     */
    public function confirmReplace(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm' => 'required|in:yes,no',
            'temp_data' => 'required|string',
        ]);

        if ($request->confirm !== 'yes') {
            return redirect()->route('settings.integrations')
                ->with('info', 'GitHub account linking cancelled.');
        }

        try {
            $tempData = decrypt($request->temp_data);
            $currentUser = auth('tenant')->user();

            // Delete existing connection
            GitHubConnection::where('user_id', $currentUser->id)->delete();

            // Create new connection
            $connection = GitHubConnection::create([
                'user_id' => $currentUser->id,
                'github_user_id' => $tempData['github_user_id'],
                'username' => $tempData['username'],
                'access_token' => $tempData['access_token'],
                'refresh_token' => $tempData['refresh_token'],
                'token_expires_at' => $tempData['expires_in'] ?
                    now()->addSeconds($tempData['expires_in']) : null,
            ]);

            // Test the connection
            $service = new GitHubService($connection);
            if (!$service->testConnection()) {
                $connection->delete();

                return redirect()->route('settings.integrations')
                    ->with('error', 'Failed to establish GitHub connection.');
            }

            return redirect()->route('settings.integrations')
                ->with('success', "GitHub account @{$tempData['username']} linked successfully!");

        } catch (\Exception $e) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Failed to link GitHub account.');
        }
    }

    /**
     * Disconnect GitHub account
     */
    public function disconnect(Request $request): RedirectResponse
    {
        $connection = GitHubConnection::where('user_id', Auth::id())->first();

        if ($connection) {
            $username = $connection->username;
            $connection->delete();

            return redirect()->back()
                ->with('success', "GitHub account @{$username} disconnected successfully.");
        }

        return redirect()->back()
            ->with('error', 'No GitHub account found to disconnect.');
    }

    /**
     * Get connection status API endpoint
     */
    public function status()
    {
        $connection = GitHubConnection::where('user_id', Auth::id())->first();

        if (!$connection) {
            return response()->json([
                'connected' => false,
                'status' => 'not_connected',
            ]);
        }

        $service = new GitHubService($connection);
        $isValid = $service->testConnection();

        return response()->json([
            'connected' => $isValid,
            'status' => $isValid ? 'connected' : 'invalid_token',
            'username' => $connection->username,
            'connected_at' => $connection->created_at->toISOString(),
            'token_expired' => $connection->isTokenExpired(),
        ]);
    }

    /**
     * Create or update GitHub connection
     */
    private function createOrUpdateConnection(User $user, $githubUser, string $token): GitHubConnection
    {
        return DB::transaction(function () use ($user, $githubUser, $token) {
            $connection = GitHubConnection::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'github_user_id' => $githubUser->getId(),
                    'username' => $githubUser->getNickname(),
                    'access_token' => $token,
                    'refresh_token' => $githubUser->refreshToken ?? null,
                    'token_expires_at' => $githubUser->expiresIn ?
                        now()->addSeconds($githubUser->expiresIn) : null,
                ]
            );

            DB::connection('landlord')->table('tenant_users')
                ->where('tenant_id', Tenant::current()->id)
                ->where('user_id', $user->id)
                ->update(['github_user_id' => $githubUser->getId()]);
                
            return $connection;
        });
    }

    /**
     * Extract first name from full name
     */
    private function extractFirstName(?string $fullName): string
    {
        if (!$fullName) {
            return 'GitHub';
        }
        $parts = explode(' ', $fullName, 2);

        return $parts[0];
    }

    /**
     * Extract last name from full name
     */
    private function extractLastName(?string $fullName): ?string
    {
        if (!$fullName) {
            return 'User';
        }
        $parts = explode(' ', $fullName, 2);

        return $parts[1] ?? null;
    }

    /**
     * Get settings integrations route with proper tenant context
     */
    private function settingsRoute(): string
    {
        $tenant = Tenant::current();
        $params = [];
        
        if ($tenant && (app()->environment('staging') || app()->isProduction())) {
            $params['account'] = $tenant->host;
        }
        
        return route('settings.integrations', $params);
    }
}
