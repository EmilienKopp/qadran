<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class GitLogResponse
{
    public function __construct(
        public string $repository,
        public string $branch,
        public Carbon $since,
        public Carbon $until,
        public Collection $commits,
        public int $totalCount
    ) {}

    public function toArray(): array
    {
        return [
            'repository' => $this->repository,
            'branch' => $this->branch,
            'since' => $this->since->toISOString(),
            'until' => $this->until->toISOString(),
            'total_count' => $this->totalCount,
            'commits' => $this->commits->map(fn($commit) => $commit->toArray())->toArray(),
        ];
    }
}