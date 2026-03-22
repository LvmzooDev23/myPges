<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'siret' => $this->siret,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'logo_path' => $this->logo_path,
            'approval_status' => $this->approval_status,
            'approved_at' => $this->approved_at,
        ];
    }
}
