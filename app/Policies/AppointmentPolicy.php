<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function cancel(User $user, Appointment $appointment): bool
    {
        return $user->isClient()
            && $appointment->client_id !== null
            && (int) $appointment->client_id === (int) $user->id;
    }
}
