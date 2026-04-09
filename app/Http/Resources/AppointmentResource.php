<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Appointment
 */
class AppointmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'doctor_id' => $this->doctor_id,
            'client_id' => $this->client_id,
            'service_id' => $this->service_id,
            'time_slot_id' => $this->time_slot_id,
            'guest_name' => $this->guest_name,
            'guest_email' => $this->guest_email,
            'guest_phone' => $this->guest_phone,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'doctor' => DoctorResource::make($this->whenLoaded('doctor')),
            'client' => UserResource::make($this->whenLoaded('client')),
            'service' => ServiceResource::make($this->whenLoaded('service')),
            'time_slot' => TimeSlotResource::make($this->whenLoaded('timeSlot')),
        ];
    }
}
