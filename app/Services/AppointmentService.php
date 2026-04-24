<?php

namespace App\Services;

use App\Events\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    public function createAppointment(User $client, array $data): Appointment
    {
        ['doctor' => $doctor, 'service' => $service, 'slot' => $slot] = $this->resolveBookingData($data);

        return DB::transaction(function () use ($client, $doctor, $service, $slot) {
            if (Appointment::where('time_slot_id', $slot->id)->activeSlot()->exists()) {
                throw ValidationException::withMessages([
                    'time_slot_id' => ['Time slot is already booked.'],
                ]);
            }

            $appointment = Appointment::create([
                'doctor_id' => $doctor->id,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'time_slot_id' => $slot->id,
                'status' => Appointment::STATUS_SCHEDULED,
            ]);

            event(new AppointmentCreated($appointment));

            return $appointment;
        });
    }

    public function rescheduleAppointment(User $client, Appointment $appointment, array $data): Appointment
    {
        ['doctor' => $doctor, 'service' => $service, 'slot' => $slot] = $this->resolveBookingData($data);

        return DB::transaction(function () use ($client, $appointment, $doctor, $service, $slot) {
            if ((int) $appointment->client_id !== (int) $client->id) {
                throw ValidationException::withMessages([
                    'appointment' => ['You can only update your own appointment.'],
                ]);
            }

            if (
                Appointment::where('time_slot_id', $slot->id)
                    ->whereKeyNot($appointment->id)
                    ->activeSlot()
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'time_slot_id' => ['Time slot is already booked.'],
                ]);
            }

            $appointment->update([
                'doctor_id' => $doctor->id,
                'service_id' => $service->id,
                'time_slot_id' => $slot->id,
                'status' => Appointment::STATUS_SCHEDULED,
            ]);

            return $appointment->fresh();
        });
    }

    public function createGuestAppointment(array $data): Appointment
    {
        ['doctor' => $doctor, 'service' => $service, 'slot' => $slot] = $this->resolveBookingData($data);

        return DB::transaction(function () use ($data, $doctor, $service, $slot) {
            if (Appointment::where('time_slot_id', $slot->id)->activeSlot()->exists()) {
                throw ValidationException::withMessages([
                    'time_slot_id' => ['Time slot is already booked.'],
                ]);
            }

            $appointment = Appointment::create([
                'doctor_id' => $doctor->id,
                'client_id' => null,
                'service_id' => $service->id,
                'time_slot_id' => $slot->id,
                'status' => Appointment::STATUS_SCHEDULED,
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'guest_phone' => $data['guest_phone'],
                'expires_at' => now()->addMinutes(5),
            ]);

            event(new AppointmentCreated($appointment));

            return $appointment;
        });
    }

    public function attachGuestAppointmentsToUser(User $user): void
    {
        Appointment::where('guest_email', $user->email)
            ->whereNull('client_id')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->update([
                'client_id' => $user->id,
                'guest_name' => null,
                'guest_email' => null,
                'guest_phone' => null,
                'expires_at' => null,
            ]);
    }

    /**
     * @return array{doctor: Doctor, service: Service, slot: TimeSlot}
     */
    private function resolveBookingData(array $data): array
    {
        /** @var Doctor $doctor */
        $doctor = Doctor::findOrFail($data['doctor_id']);
        /** @var Service $service */
        $service = Service::findOrFail($data['service_id']);
        /** @var TimeSlot|null $slot */
        $slot = TimeSlot::whereKey($data['time_slot_id'])
            ->where('doctor_id', $doctor->id)
            ->future()
            ->first();

        if (! $slot) {
            throw ValidationException::withMessages([
                'time_slot_id' => ['The selected time is unavailable or has already passed.'],
            ]);
        }

        if (! $doctor->services()->whereKey($service->id)->exists()) {
            throw ValidationException::withMessages([
                'service_id' => ['Doctor does not provide this service.'],
            ]);
        }

        return [
            'doctor' => $doctor,
            'service' => $service,
            'slot' => $slot,
        ];
    }
}
