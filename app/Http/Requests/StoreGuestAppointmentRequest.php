<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:50'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'guest_name' => 'имя',
            'guest_email' => 'email',
            'guest_phone' => 'номер телефона',
        ];
    }
}
