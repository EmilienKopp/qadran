<?php

namespace App\Repositories\Remote;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class RemoteUserRepository extends BaseRemoteRepository implements UserRepositoryInterface
{
    protected string $model = User::class;

    public function find(int $id, ?array $relations = null): ?User
    {
        try {
            $data = $this->get("{$this->resourceEndpoint}/{$id}", $relations ? ['include' => implode(',', $relations)] : []);
            \Log::debug('RemoteUserRepository find data', ['data' => $data]);
            return $this->hydrate($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findByWorkosId(string $workosId): ?User
    {
        try {
            $data = $this->get("{$this->resourceEndpoint}/by-workos-id/{$workosId}");
            \Log::debug('RemoteUserRepository findByWorkosId data', ['data' => $data, 'workosId' => $workosId]);
            return $this->hydrate($data);
        } catch (\Exception $e) {
            \Log::error('RemoteUserRepository findByWorkosId failed', ['error' => $e->getMessage(), 'workosId' => $workosId]);
            return null;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $data = $this->get($this->resourceEndpoint, ['email' => $email]);
            // Assuming the API returns a single user or array with one user
            if (is_array($data) && !empty($data)) {
                return $this->hydrate(is_array($data[0]) ? $data[0] : $data);
            }
            return $this->hydrate($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function all(): \Illuminate\Support\Collection
    {
        $data = $this->get($this->resourceEndpoint);
        return $this->hydrateCollection($data);
    }

    public function create(array $data): User
    {
        $responseData = $this->post($this->resourceEndpoint, $data);
        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?User
    {
        try {
            $responseData = $this->put("{$this->resourceEndpoint}/{$id}", $data);
            return $this->hydrate($responseData);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete(int $id): bool
    {
        return $this->deleteRequest("{$this->resourceEndpoint}/{$id}");
    }
}
