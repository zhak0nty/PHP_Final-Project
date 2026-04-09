<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Doctor
 */
class DoctorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'user' => UserResource::make($this->whenLoaded('user')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
