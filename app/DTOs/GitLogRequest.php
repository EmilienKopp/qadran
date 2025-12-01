<?php

namespace App\DTOs;

use Carbon\Carbon;

class GitLogRequest
{
    public function __construct(
        public string $repository,
        public string $branch,
        public ?Carbon $since,
        public ?Carbon $until,
        public bool $includeDiff = false,
        public ?string $author = null
    ) {}

    public static function create(array $data): self
    {
        return new self(
            repository: $data['repository'],
            branch: $data['branch'],
            since: isset($data['since']) ? Carbon::parse($data['since']) : null,
            until: isset($data['until']) ? Carbon::parse($data['until']) : null,
            includeDiff: $data['include_diff'] ?? false,
            author: $data['author'] ?? null
        );
    }
}
