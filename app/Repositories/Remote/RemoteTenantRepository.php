<?php

namespace App\Repositories\Remote;

use App\Models\Landlord\Tenant;
use App\Repositories\TenantRepositoryInterface;

class RemoteTenantRepository extends BaseRemoteRepository implements TenantRepositoryInterface
{
    protected string $model = Tenant::class;

    public function find(int $id): ?Tenant
    {
        try {
            $data = $this->get("{$this->resourceEndpoint}/{$id}");
            return $this->hydrate($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findByDomain(string $domain): ?Tenant
    {
        try {
            $data = $this->get($this->resourceEndpoint, ['domain' => $domain]);
            // Assuming the API returns a single tenant or array with one tenant
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

    public function create(array $data): Tenant
    {
        $responseData = $this->post($this->resourceEndpoint, $data);
        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?Tenant
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
