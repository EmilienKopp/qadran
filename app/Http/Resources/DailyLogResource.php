<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'project_id' => $this->project_id,
            'project_name' => $this->project_name,
            'date' => $this->date,
            'total_seconds' => $this->total_seconds,
            'total_minutes' => $this->total_minutes,
            'activities' => ActivityResource::collection($this->whenLoaded('activities') ?? $this->activities ?? []),
            'timeLogs' => ClockEntryResource::collection($this->whenLoaded('timeLogs') ?? $this->timeLogs ?? []),
        ];
    }
}
