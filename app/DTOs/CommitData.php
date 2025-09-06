<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class CommitData
{
    private ?Collection $diff = null;

    public function __construct(
        public string $sha,
        public string $message,
        public string $author,
        public string $authorEmail,
        public Carbon $date,
        public string $url
    ) {}

    public function setDiff(Collection $diff): void
    {
        $this->diff = $diff;
    }

    public function getDiff(): ?Collection
    {
        return $this->diff;
    }

    public function toArray(): array
    {
        $data = [
            'sha' => $this->sha,
            'message' => $this->message,
            'author' => $this->author,
            'author_email' => $this->authorEmail,
            'date' => $this->date->toISOString(),
            'url' => $this->url,
        ];

        if ($this->diff !== null) {
            $data['diff'] = $this->diff->map(fn($file) => $file->toArray())->toArray();
        }

        return $data;
    }
}
