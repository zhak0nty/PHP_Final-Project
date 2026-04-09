<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Notifications\AppointmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyAppointmentParticipants implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $appointmentId
    ) {}

    public function handle(): void
    {
        $appointment = Appointment::query()
            ->with(['doctor.user', 'client'])
            ->find($this->appointmentId);

        if (! $appointment) {
            return;
        }

        $appointment->doctor?->user?->notify(new AppointmentCreatedNotification($appointment));

        if ($appointment->client_id && $appointment->client) {
            $appointment->client->notify(new AppointmentCreatedNotification($appointment));
        }
    }
}
