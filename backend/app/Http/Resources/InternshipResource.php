<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternshipResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'slots' => $this->slots,
            'status' => $this->status,
            'requirements' => $this->requirements,
            'applications_count' => $this->when(isset($this->applications_count), $this->applications_count),
            'created_at' => $this->created_at,
        ];
    }
}
