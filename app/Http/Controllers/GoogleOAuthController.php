<?php

namespace App\Http\Controllers;

use App\Models\GoogleConnection;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Support\RequestContextResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Native\Laravel\Facades\Settings;

class GoogleOAuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Redirect to Google OAuth for authentication or account linking
     */
    public function redirect(): RedirectResponse
    {
        // No authentication check - supports both login and linking
        // Scopes can be configured in config/services.php
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback - routes to authentication or linking
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // Access token through property (Laravel Socialite provides this dynamically)
            // @phpstan-ignore-next-line
            $token = $googleUser->token ?? '';

            // Check if user is authenticated - determines flow
            if (Auth::check()) {
                return $this->handleAccountLinking(Auth::user(), $googleUser, $token);
            }

            return $this->handleAuthentication($googleUser, $token);

        } catch (InvalidStateException $e) {
            if (Auth::check()) {
                return redirect()->route('settings.integrations')
                    ->with('error', 'Invalid OAuth state. Please try again.');
            }

            return redirect('/')
                ->with('error', 'Invalid OAuth state. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            if (Auth::check()) {
                return redirect()->route('settings.integrations')
                    ->with('error', 'An error occurred during Google authentication.');
            }

            return redirect('/')
                ->with('error', 'An error occurred during Google authentication.');
        }
    }

    /**
     * Handle authentication flow (login/registration)
     */
    private function handleAuthentication($googleUser, string $token): RedirectResponse
    {
        // Lookup user by Google ID
        $user = $this->userRepository->findByGoogleId($googleUser->getId());

        // Create user if doesn't exist (web mode only)
        if (! $user) {
            if (RequestContextResolver::isDesktop()) {
                return redirect('/')
                    ->with('error', 'User not found. Please sign up via web first.');
            }

            // Validate email is present
            if (! $googleUser->getEmail()) {
                return redirect('/')
                    ->with('error', 'Unable to retrieve email from Google. Please try again.');
            }

            $user = User::create([
                'first_name' => $googleUser->user['given_name'] ?? $this->extractFirstName($googleUser->getName()),
                'last_name' => $googleUser->user['family_name'] ?? $this->extractLastName($googleUser->getName()),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]);
        }

        // Create or update Google connection
        $this->createOrUpdateConnection($user, $googleUser, $token);

        // Login user
        Auth::guard('tenant')->login($user);

        \Log::info('Google authentication successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'has_tenant' => \App\Models\Landlord\Tenant::current() !== null,
            'session_id' => request()->session()->getId(),
        ]);

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
        $tenant = \App\Models\Landlord\Tenant::current();

        if ($tenant) {
            // On tenant subdomain - redirect to dashboard
            $params = [];
            if (app()->environment('staging') || app()->isProduction()) {
                $params['account'] = $tenant->host;
            }

            return redirect()->route('dashboard', $params);
        }

        // No tenant context - user needs to select/create organization
        // Show landing page with success message
        return redirect('/')
            ->with('success', 'Successfully logged in with Google! Please select or create your organization.');
    }

    /**
     * Handle account linking flow (for already authenticated users)
     */
    private function handleAccountLinking(User $currentUser, $googleUser, string $token): RedirectResponse
    {
        // Check if this Google account is already linked to another user
        $existingConnection = GoogleConnection::where('google_user_id', $googleUser->getId())
            ->where('user_id', '!=', $currentUser->id)
            ->first();

        if ($existingConnection) {
            return redirect()->route('settings.integrations')
                ->with('error', 'This Google account is already linked to another user.');
        }

        // Check if current user already has a Google connection
        $currentConnection = GoogleConnection::where('user_id', $currentUser->id)->first();

        if ($currentConnection && $currentConnection->google_user_id != $googleUser->getId()) {
            // User is trying to link a different Google account
            return redirect()->route('settings.integrations')
                ->with('confirm_replace', [
                    'message' => 'You already have a Google account linked. Do you want to replace it?',
                    'current_email' => $currentConnection->email,
                    'new_email' => $googleUser->getEmail(),
                    'temp_data' => encrypt([
                        'google_user_id' => $googleUser->getId(),
                        'email' => $googleUser->getEmail(),
                        'access_token' => $token,
                        'refresh_token' => $googleUser->refreshToken ?? null,
                        'expires_in' => $googleUser->expiresIn ?? null,
                    ]),
                ]);
        }

        // Create or update the connection
        $this->createOrUpdateConnection($currentUser, $googleUser, $token);

        return redirect()->route('settings.integrations')
            ->with('success', "Google account {$googleUser->getEmail()} linked successfully!");
    }

    /**
     * Confirm replacement of existing Google connection
     */
    public function confirmReplace(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm' => 'required|in:yes,no',
            'temp_data' => 'required|string',
        ]);

        if ($request->confirm !== 'yes') {
            return redirect()->route('settings.integrations')
                ->with('info', 'Google account linking cancelled.');
        }

        try {
            $tempData = decrypt($request->temp_data);
            $currentUser = Auth::user();

            // Delete existing connection
            GoogleConnection::where('user_id', $currentUser->id)->delete();

            // Create new connection
            GoogleConnection::create([
                'user_id' => $currentUser->id,
                'google_user_id' => $tempData['google_user_id'],
                'email' => $tempData['email'],
                'access_token' => $tempData['access_token'],
                'refresh_token' => $tempData['refresh_token'],
                'token_expires_at' => $tempData['expires_in'] ?
                    now()->addSeconds($tempData['expires_in']) : null,
            ]);

            return redirect()->route('settings.integrations')
                ->with('success', "Google account {$tempData['email']} linked successfully!");

        } catch (\Exception $e) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Failed to link Google account.');
        }
    }

    /**
     * Disconnect Google account
     */
    public function disconnect(Request $request): RedirectResponse
    {
        $connection = GoogleConnection::where('user_id', Auth::id())->first();

        if ($connection) {
            $email = $connection->email;
            $connection->delete();

            return redirect()->back()
                ->with('success', "Google account {$email} disconnected successfully.");
        }

        return redirect()->back()
            ->with('error', 'No Google account found to disconnect.');
    }

    /**
     * Get connection status API endpoint
     */
    public function status()
    {
        $connection = GoogleConnection::where('user_id', Auth::id())->first();

        if (! $connection) {
            return response()->json([
                'connected' => false,
                'status' => 'not_connected',
            ]);
        }

        return response()->json([
            'connected' => true,
            'status' => 'connected',
            'email' => $connection->email,
            'connected_at' => $connection->created_at->toISOString(),
            'token_expired' => $connection->isTokenExpired(),
        ]);
    }

    /**
     * Create or update Google connection
     */
    private function createOrUpdateConnection(User $user, $googleUser, string $token): GoogleConnection
    {
        return GoogleConnection::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'google_user_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'access_token' => $token,
                'refresh_token' => $googleUser->refreshToken ?? null,
                'token_expires_at' => $googleUser->expiresIn ?
                    now()->addSeconds($googleUser->expiresIn) : null,
            ]
        );
    }

    /**
     * Extract first name from full name
     */
    private function extractFirstName(?string $fullName): string
    {
        if (! $fullName) {
            return 'Google';
        }
        $parts = explode(' ', $fullName, 2);

        return $parts[0];
    }

    /**
     * Extract last name from full name
     */
    private function extractLastName(?string $fullName): ?string
    {
        if (! $fullName) {
            return 'User';
        }
        $parts = explode(' ', $fullName, 2);

        return $parts[1] ?? null;
    }
}
