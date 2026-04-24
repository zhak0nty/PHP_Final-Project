<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
        ];
    }
}
