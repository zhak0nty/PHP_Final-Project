<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\NotifyAppointmentParticipants;

class DispatchAppointmentNotificationJob
{
    public function handle(AppointmentCreated $event): void
    {
        NotifyAppointmentParticipants::dispatch($event->appointment->id);
    }
}
