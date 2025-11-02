<?php

namespace App\Repositories\Remote;

use App\Models\Project;
use App\Repositories\ProjectRepositoryInterface;

class RemoteProjectRepository extends BaseRemoteRepository implements ProjectRepositoryInterface
{
    protected string $model = Project::class;

    public function find(int $id): ?Project
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

    public function findByOrganization(int $organizationId): \Illuminate\Support\Collection
    {
        $data = $this->get($this->resourceEndpoint, ['organization_id' => $organizationId]);
        return $this->hydrateCollection($data);
    }

    public function create(array $data): Project
    {
        $responseData = $this->post($this->resourceEndpoint, $data);
        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?Project
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
