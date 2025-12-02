<?php

namespace App\Repositories\Local;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;

class LocalUserRepository implements UserRepositoryInterface
{
    public function with(array $relations): UserRepositoryInterface
    {
        // This is a no-op for the local repository
        return $this;
    }

    public function find(int $id, $relations = null): ?User
    {
        if ($relations) {
            return User::with($relations)->find($id);
        }

        return User::find($id);
    }

    public function findByWorkosId(string $workosId): ?User
    {
        $user = User::where('workos_id', $workosId)->first();
        \Log::debug('LocalUserRepository findByWorkosId user', ['user' => $user]);

        return $user;
    }

    public function findByGitHubId(string $githubUserId): ?User
    {
        return User::whereHas('gitHubConnection', function ($query) use ($githubUserId) {
            $query->where('github_user_id', $githubUserId);
        })->first();
    }

    public function findByGoogleId(string $googleUserId): ?User
    {
        return User::whereHas('googleConnection', function ($query) use ($googleUserId) {
            $query->where('google_user_id', $googleUserId);
        })->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getGitHubUserId(int $userId): ?string
    {
        $user = User::find($userId);
        return $user?->gitHubConnection?->github_user_id;
    }

    public function all(): Collection
    {
        return User::all();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);
        if (! $user) {
            return null;
        }
        $user->update($data);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = User::find($id);
        if (! $user) {
            return false;
        }

        return $user->delete();
    }
}
