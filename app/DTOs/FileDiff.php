<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class FileDiff
{
    public function __construct(
        public string $filename,
        public string $status,
        public int $additions,
        public int $deletions,
        public int $changes,
        public ?string $patch = null,
        public ?string $blobUrl = null
    ) {}

    public function toArray(): array
    {
        return [
            'filename' => $this->filename,
            'status' => $this->status,
            'additions' => $this->additions,
            'deletions' => $this->deletions,
            'changes' => $this->changes,
            'patch' => $this->patch,
            'blob_url' => $this->blobUrl,
        ];
    }
}