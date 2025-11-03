<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'clock_entry_id' => $this->clock_entry_id,
            'activity_type_id' => $this->activity_type_id,
            'task_id' => $this->task_id,
            'start_offset_seconds' => $this->start_offset_seconds,
            'end_offset_seconds' => $this->end_offset_seconds,
            'duration_seconds' => $this->duration_seconds,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'clock_entry' => new ClockEntryResource($this->whenLoaded('clockEntry')),
            'activity_type' => new ActivityTypeResource($this->whenLoaded('activityType')),
            'task' => new TaskResource($this->whenLoaded('task')),
        ];
    }
}
