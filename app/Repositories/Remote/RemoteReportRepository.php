<?php

namespace App\Repositories\Remote;

use App\Models\Report;
use App\Repositories\ReportRepositoryInterface;

class RemoteReportRepository extends BaseRemoteRepository implements ReportRepositoryInterface
{
    protected string $model = Report::class;

    public function find(int $id): ?Report
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

    public function findByProject(int $projectId): \Illuminate\Support\Collection
    {
        $data = $this->get($this->resourceEndpoint, ['project_id' => $projectId]);

        return $this->hydrateCollection($data);
    }

    public function create(array $data): Report
    {
        $responseData = $this->post($this->resourceEndpoint, $data);

        return $this->hydrate($responseData);
    }

    public function update(int $id, array $data): ?Report
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
