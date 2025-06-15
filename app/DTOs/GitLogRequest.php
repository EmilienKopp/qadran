<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class GitLogRequest
{
    public function __construct(
        public string $repository,
        public string $branch,
        public ?Carbon $since,
        public Carbon $until,
        public bool $includeDiff = false,
        public ?string $author = null
    ) {}

    public static function create(array $data): self
    {
        return new self(
            repository: $data['repository'],
            branch: $data['branch'],
            since: Carbon::parse($data['since']),
            until: Carbon::parse($data['until']),
            includeDiff: $data['include_diff'] ?? false,
            author: $data['author'] ?? null
        );
    }
}