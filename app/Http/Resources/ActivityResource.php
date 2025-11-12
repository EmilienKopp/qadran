<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'task_category_id' => $this->task_category_id,
            'date' => $this->date,
            'duration' => $this->duration,
            'notes' => $this->notes,
            'task_category' => $this->whenLoaded('taskCategory'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
