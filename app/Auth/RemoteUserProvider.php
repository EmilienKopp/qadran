<?php

namespace App\Auth;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Native\Desktop\Facades\Settings;

class RemoteUserProvider implements UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     */
    public function retrieveById($identifier)
    {
        // For authentication, we need to use the local tenant database directly
        // to avoid circular dependency with remote repositories
        try {
            $fromSettings = Settings::get('authenticated_user');
            if ($fromSettings && isset($fromSettings['id']) && $fromSettings['id'] == $identifier) {
                $user = new User;
                $user->forceFill($fromSettings);
                $user->exists = true;

                return $user;
            } else {
                $user = app(UserRepositoryInterface::class)->find($identifier);
                Settings::set('authenticated_user', $user ? $user->toArray() : null);

                return $user;
            }

        } catch (\Exception $e) {
            \Log::error('RemoteUserProvider::retrieveById failed', [
                'identifier' => $identifier,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     */
    public function retrieveByToken($identifier, $token)
    {
        // Not implemented for desktop mode
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not implemented for desktop mode
    }

    /**
     * Retrieve a user by the given credentials.
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        // For authentication, use local database directly to avoid circular dependency
        if (isset($credentials['email'])) {
            try {
                return User::where('email', $credentials['email'])->first();
            } catch (\Exception $e) {
                \Log::error('RemoteUserProvider::retrieveByCredentials failed', [
                    'email' => $credentials['email'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        }

        return null;
    }

    /**
     * Validate a user against the given credentials.
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // For desktop mode with WorkOS, credential validation happens externally
        // This is only called for password-based authentication
        return false;
    }

    /**
     * Rehash the user's password if required and supported.
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Not applicable for desktop mode
    }
}
