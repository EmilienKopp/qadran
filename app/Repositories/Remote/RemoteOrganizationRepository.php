<?php

namespace App\Repositories\Remote;

use App\Models\Organization;
use App\Repositories\OrganizationRepositoryInterface;

class RemoteOrganizationRepository extends BaseRemoteRepository implements OrganizationRepositoryInterface
{
    protected string $model = Organization::class;

    public function find(int $id): ?Organization
    {
        try {
            $data = $this->get("{$this->resourceEndpoint}/{$id}");
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

    public function findByUser(int $userId): \Illuminate\Support\Collection
    {
        $data = $this->get($this->resourceEndpoint, ['user_id' => $userId]);
        return $this->hydrateCollection($data);
    }

    public function create(array $data): Organization
    {
        $responseData = $this->post($this->resourceEndpoint, $data);
        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?Organization
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
