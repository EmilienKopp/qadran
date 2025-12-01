<?php

namespace App\Repositories;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Collection;

interface TenantRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Tenant;

    public function findByDomain(string $domain): ?Tenant;

    public function all(): Collection;

    public function create(array $data): Tenant;

    public function update(int $id, array $data): ?Tenant;

    public function delete(int $id): bool;
}
