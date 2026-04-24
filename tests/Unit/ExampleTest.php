<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_service_creates_appointment_for_valid_data(): void
    {
        $this->seed();

        $service = app(AppointmentService::class);
        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $doctorService = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        $appointment = $service->createAppointment($client, [
            'doctor_id' => $doctor->id,
            'service_id' => $doctorService->id,
            'time_slot_id' => $slot->id,
        ]);

        $this->assertSame($client->id, $appointment->client_id);
        $this->assertSame(Appointment::STATUS_SCHEDULED, $appointment->status);
    }

    public function test_appointment_service_rejects_taken_slot(): void
    {
        $this->seed();

        $service = app(AppointmentService::class);
        $client = User::where('role', User::ROLE_CLIENT)->firstOrFail();
        $doctor = Doctor::firstOrFail();
        $doctorService = $doctor->services()->firstOrFail();
        $slot = TimeSlot::where('doctor_id', $doctor->id)->firstOrFail();

        Appointment::create([
            'doctor_id' => $doctor->id,
            'client_id' => $client->id,
            'service_id' => $doctorService->id,
            'time_slot_id' => $slot->id,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->expectException(ValidationException::class);

        $service->createAppointment($client, [
            'doctor_id' => $doctor->id,
            'service_id' => $doctorService->id,
            'time_slot_id' => $slot->id,
        ]);
    }
}

