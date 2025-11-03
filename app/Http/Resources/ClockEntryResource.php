<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClockEntryResource extends JsonResource
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
            'project_id' => $this->project_id,
            'in' => $this->in,
            'out' => $this->out,
            'timezone' => $this->timezone,
            'notes' => $this->notes,
            'duration_seconds' => $this->duration_seconds,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
