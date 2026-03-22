<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'student_number' => $this->student_number,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'university' => $this->university,
            'degree' => $this->degree,
            'skills' => $this->skills,
            'linkedin_url' => $this->linkedin_url,
            'cv_path' => $this->cv_path,
            'has_cv' => (bool) $this->cv_path,
            'supervisor_id' => $this->supervisor_id,
            'supervisor' => SupervisorResource::make($this->whenLoaded('supervisor')),
            'created_at' => $this->created_at,
        ];
    }
}
