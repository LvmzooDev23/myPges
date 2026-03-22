<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternshipReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'title' => $this->title,
            'status' => $this->status,
            'feedback' => $this->feedback,
            'validated_at' => $this->validated_at,
            'submitted_at' => $this->submitted_at,
            'supervisor' => SupervisorResource::make($this->whenLoaded('supervisor')),
        ];
    }
}
