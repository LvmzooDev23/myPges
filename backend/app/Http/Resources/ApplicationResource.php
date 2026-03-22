<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'internship' => new InternshipResource($this->whenLoaded('internship')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'status' => $this->status,
            'cover_letter' => $this->cover_letter,
            'applied_at' => $this->applied_at,
            'report' => InternshipReportResource::make($this->whenLoaded('report')),
            'evaluation' => EvaluationResource::make($this->whenLoaded('evaluation')),
        ];
    }
}
