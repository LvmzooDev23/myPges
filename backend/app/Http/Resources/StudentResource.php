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
            'student_number' => $this->student_number,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'cv_path' => $this->cv_path,
            'supervisor_id' => $this->supervisor_id,
            'supervisor' => SupervisorResource::make($this->whenLoaded('supervisor')),
            'created_at' => $this->created_at,
        ];
    }
}
