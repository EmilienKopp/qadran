<?php

namespace App\DTOs;


use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class ClockEntryDTO extends DTO
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public ?int $projectId,
        public Carbon $in,
        public ?Carbon $out,
        public ?string $note,
        public ?string $timezone,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {}
}