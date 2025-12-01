<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class GitHubAccountService
{
    /**
     * Check if user has a valid GitHub connection
     */
    public function hasValidConnection(User $user): bool
    {
        $connection = $user->gitHubConnection;

        if (! $connection) {
            return false;
        }

        if ($connection->isTokenExpired()) {
            return false;
        }

        // Test the connection
        $service = new GitHubService($connection);

        return $service->testConnection();
    }

    /**
     * Get connection status for user
     */
    public function getConnectionStatus(User $user): array
    {
        $connection = $user->gitHubConnection;

        if (! $connection) {
            return [
                'connected' => false,
                'status' => 'not_connected',
                'message' => 'No GitHub account connected',
            ];
        }

        if ($connection->isTokenExpired()) {
            return [
                'connected' => false,
                'status' => 'token_expired',
                'message' => 'GitHub token has expired',
                'username' => $connection->username,
                'connected_at' => $connection->created_at,
            ];
        }

        $service = new GitHubService($connection);
        $isValid = $service->testConnection();

        return [
            'connected' => $isValid,
            'status' => $isValid ? 'connected' : 'invalid_token',
            'message' => $isValid ? 'Connected successfully' : 'Invalid or revoked token',
            'username' => $connection->username,
            'connected_at' => $connection->created_at,
            'repositories_count' => $isValid ? $service->getRepositories()->count() : 0,
        ];
    }

    /**
     * Refresh connection token if possible
     */
    public function refreshConnection(User $user): bool
    {
        $connection = $user->gitHubConnection;

        if (! $connection || ! $connection->refresh_token) {
            return false;
        }

        try {
            // GitHub doesn't typically use refresh tokens in the same way
            // You might need to implement token refresh logic here
            // For now, we'll just test the existing token
            $service = new GitHubService($connection);

            return $service->testConnection();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user's GitHub repositories with cached settings
     */
    public function getRepositoriesWithSettings(User $user): Collection
    {
        if (! $this->hasValidConnection($user)) {
            return collect();
        }

        $service = GitHubService::forUser($user->id);
        $repositories = $service->getRepositories();

        // Attach settings for each repository
        return $repositories->map(function ($repo) use ($service) {
            $settings = collect();

            // Get branches and their settings
            try {
                $branches = $service->getBranches($repo['full_name']);
                $branches->each(function ($branch) use ($service, $repo, &$settings) {
                    $branchSettings = $service->getRepositorySettings(
                        $repo['full_name'],
                        $branch['name']
                    );

                    if ($branchSettings) {
                        $settings->push([
                            'branch' => $branch['name'],
                            'settings' => $branchSettings,
                        ]);
                    }
                });
            } catch (\Exception $e) {
                // Handle API errors gracefully
            }

            $repo['configured_branches'] = $settings;

            return $repo;
        });
    }
}
