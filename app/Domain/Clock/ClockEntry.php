<?php

namespace App\Domain\Clock;

use App\DTOs\ClockEntryDTO;
use Carbon\Carbon;

class ClockEntry
{
    public ?int $id;

    public int $userId;

    public ?int $projectId;

    public ?Carbon $in;

    public ?Carbon $out;

    public ?string $note;

    public ?string $timezone;

    public ?Carbon $createdAt;

    public ?Carbon $updatedAt;

    public function __construct(
        ClockEntryDTO|array $data
    ) {
        if (is_array($data)) {
            $data = new ClockEntryDTO(...$data);
        }

        $this->id = $data->id;
        $this->userId = $data->userId;
        $this->projectId = $data->projectId;
        $this->in = $data->in;
        $this->out = $data->out;
        $this->note = $data->note;
        $this->timezone = $data->timezone;
        $this->createdAt = $data->createdAt;
        $this->updatedAt = $data->updatedAt;
    }

    public function isClockedOut(): bool
    {
        return $this->out !== null;
    }

    public function isClockedIn(): bool
    {
        return ! $this->isClockedOut() && ! empty($this->in);
    }

    public function clockIn(Carbon $time): void
    {
        $this->in = $time;
        $this->out = null;
    }

    public function clockOut(Carbon $time): void
    {
        $this->out = $time;
    }

    public function autoClock()
    {
        $now = Carbon::now();
        if ($this->in->isSameDay($now) === false) {
            $this->in = null;
            $this->out = null;

            return;
        }
        if ($this->isClockedIn()) {
            $this->clockOut(Carbon::now());
        } else {
            $this->clockIn(Carbon::now());
        }
    }

    public function toArray(): array
    {
        return $this->toDTO()->toArray();
    }

    public function toDTO(): ClockEntryDTO
    {
        return new ClockEntryDTO(
            id: $this->id,
            userId: $this->userId,
            projectId: $this->projectId,
            in: $this->in,
            out: $this->out,
            note: $this->note,
            timezone: $this->timezone,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
        );
    }
}
