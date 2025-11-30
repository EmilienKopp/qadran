<?php

namespace App\Http\Controllers;

use App\Models\GitHubConnection;
use App\Services\GitHubService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GitHubOAuthController extends Controller
{
    /**
     * Redirect to GitHub OAuth for account linking
     */
    public function redirect(): RedirectResponse
    {
        // Ensure user is authenticated before linking
        // if (!Auth::check()) {
        //     return redirect()->route('login')
        //         ->with('error', 'Please log in first to connect your GitHub account.');
        // }


        return Socialite::driver('github')
            ->scopes(['repo', 'user:email'])
            ->redirect();
    }

    /**
     * Handle GitHub OAuth callback and link to existing user
     */
    public function callback(Request $request)
    {
        // Double-check authentication
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please log in and try again.');
        }

        try {
            $githubUser = Socialite::driver('github')->user();
            $currentUser = Auth::user();
            
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
                            'access_token' => $githubUser->token,
                            'refresh_token' => $githubUser->refreshToken,
                            'expires_in' => $githubUser->expiresIn,
                        ])
                    ]);
            }

            // Create or update the connection
            $connection = $this->createOrUpdateConnection($currentUser->id, $githubUser);

            // Test the connection
            $service = new GitHubService($connection);
            if (!$service->testConnection()) {
                $connection->delete();
                return redirect()->route('settings.integrations')
                    ->with('error', 'Failed to establish GitHub connection. Please try again.');
            }

            return redirect()->route('settings.integrations')
                ->with('success', "GitHub account @{$githubUser->getNickname()} linked successfully!");

        } catch (InvalidStateException $e) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Invalid OAuth state. Please try again.');
        } catch (\Exception $e) {
            \Log::error('GitHub OAuth callback error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('settings.integrations')
                ->with('error', 'An error occurred while connecting your GitHub account.');
        }
    }

    /**
     * Confirm replacement of existing GitHub connection
     */
    public function confirmReplace(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm' => 'required|in:yes,no',
            'temp_data' => 'required|string'
        ]);

        if ($request->confirm !== 'yes') {
            return redirect()->route('settings.integrations')
                ->with('info', 'GitHub account linking cancelled.');
        }

        try {
            $tempData = decrypt($request->temp_data);
            $currentUser = Auth::user();

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
                'status' => 'not_connected'
            ]);
        }

        $service = new GitHubService($connection);
        $isValid = $service->testConnection();

        return response()->json([
            'connected' => $isValid,
            'status' => $isValid ? 'connected' : 'invalid_token',
            'username' => $connection->username,
            'connected_at' => $connection->created_at->toISOString(),
            'token_expired' => $connection->isTokenExpired()
        ]);
    }

    /**
     * Create or update GitHub connection
     */
    private function createOrUpdateConnection(int $userId, $githubUser): GitHubConnection
    {
        return GitHubConnection::updateOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'github_user_id' => $githubUser->getId(),
                'username' => $githubUser->getNickname(),
                'access_token' => $githubUser->token,
                'refresh_token' => $githubUser->refreshToken,
                'token_expires_at' => $githubUser->expiresIn ? 
                    now()->addSeconds($githubUser->expiresIn) : null,
            ]
        );
    }
}