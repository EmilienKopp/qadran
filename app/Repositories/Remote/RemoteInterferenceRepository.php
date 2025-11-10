<?php

namespace App\Repositories\Remote;

use App\Models\Interference;
use App\Repositories\InterferenceRepositoryInterface;
use Carbon\Carbon;

class RemoteInterferenceRepository extends BaseRemoteRepository implements InterferenceRepositoryInterface
{
    protected string $model = Interference::class;

    public function find(int $id): ?Interference
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

    public function register(array $data): Interference
    {
        $responseData = $this->post($this->resourceEndpoint, $data);
        return $this->hydrate($responseData);
    }

    public function getInterferencesForUser(
        int $userId,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): \Illuminate\Support\Collection {
        $params = ['user_id' => $userId];
        
        if ($startDate) {
            $params['start_date'] = $startDate->toDateString();
        }
        
        if ($endDate) {
            $params['end_date'] = $endDate->toDateString();
        }
        
        $data = $this->get($this->resourceEndpoint, $params);
        return $this->hydrateCollection($data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteRequest("{$this->resourceEndpoint}/{$id}");
    }
}
