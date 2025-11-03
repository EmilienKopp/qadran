<?php

namespace App\Repositories\Local;

use App\Models\Landlord\Tenant;
use App\Repositories\TenantRepositoryInterface;
use Illuminate\Support\Collection;

class LocalTenantRepository implements TenantRepositoryInterface
{
    public function find(int $id): ?Tenant
    {
        return Tenant::find($id);
    }

    public function findByDomain(string $domain): ?Tenant
    {
        return Tenant::where('domain', $domain)->first();
    }

    public function all(): Collection
    {
        return Tenant::all();
    }

    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function update(int $id, array $data): ?Tenant
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return null;
        }
        $tenant->update($data);
        return $tenant->fresh();
    }

    public function delete(int $id): bool
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return false;
        }
        return $tenant->delete();
    }
}
