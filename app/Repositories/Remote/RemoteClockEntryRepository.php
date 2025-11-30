<?php

namespace App\Repositories\Remote;

use App\Models\ClockEntry;
use App\Repositories\ClockEntryRepositoryInterface;

class RemoteClockEntryRepository extends BaseRemoteRepository implements ClockEntryRepositoryInterface
{
    protected string $model = ClockEntry::class;

    public function find(int $id): ?ClockEntry
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

    public function findActiveByUser(int $userId): ?ClockEntry
    {
        try {
            $data = $this->get($this->resourceEndpoint, [
                'user_id' => $userId,
                'active' => true,
            ]);
            // Assuming the API returns a single active entry or array with one entry
            if (is_array($data) && ! empty($data)) {
                return $this->hydrate(is_array($data[0]) ? $data[0] : $data);
            }

            return $this->hydrate($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(array $data): ClockEntry
    {
        $responseData = $this->post($this->resourceEndpoint, $data);

        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?ClockEntry
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
