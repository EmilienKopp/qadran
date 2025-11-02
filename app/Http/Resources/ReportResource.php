<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->resource['date'] ?? null,
            'entries' => ClockEntryResource::collection($this->resource['entries'] ?? []),
            'total_hours' => $this->resource['total_hours'] ?? 0,
            'total_seconds' => $this->resource['total_seconds'] ?? 0,
            'projects' => $this->resource['projects'] ?? [],
        ];
    }
}
