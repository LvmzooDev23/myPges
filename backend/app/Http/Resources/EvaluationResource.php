<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'score' => $this->score,
            'criteria' => $this->criteria,
            'comments' => $this->comments,
            'supervisor' => SupervisorResource::make($this->whenLoaded('supervisor')),
            'student' => StudentResource::make($this->whenLoaded('student')),
        ];
    }
}
