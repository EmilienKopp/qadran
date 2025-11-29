<?php

namespace App\Repositories\Remote;

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepositoryInterface;
use Illuminate\Support\Collection;

class RemoteActivityLogRepository extends BaseRemoteRepository implements ActivityLogRepositoryInterface
{
    protected string $model = ActivityLog::class;

    public function find(int $id): ?ActivityLog
    {
        try {
            $data = $this->get("{$this->resourceEndpoint}/{$id}");

            return $this->hydrate($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function all(): Collection
    {
        $data = $this->get($this->resourceEndpoint);

        return $this->hydrateCollection($data);
    }

    public function findByClockEntry(int $clockEntryId): Collection
    {
        $data = $this->get($this->resourceEndpoint, ['clock_entry_id' => $clockEntryId]);

        return $this->hydrateCollection($data);
    }

    public function findByUserAndDate(int $userId, string $date): Collection
    {
        $data = $this->get($this->resourceEndpoint, [
            'user_id' => $userId,
            'date' => $date,
        ]);

        return $this->hydrateCollection($data);
    }

    public function sync(array $validated): void
    {
        $this->post("{$this->resourceEndpoint}/sync", $validated);
    }

    public function create(array $data): ActivityLog
    {
        $responseData = $this->post($this->resourceEndpoint, $data);

        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?ActivityLog
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
