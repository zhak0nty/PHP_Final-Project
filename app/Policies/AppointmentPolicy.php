<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $this->ownsAppointment($user, $appointment);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $this->ownsAppointment($user, $appointment);
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $this->ownsAppointment($user, $appointment);
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $this->ownsAppointment($user, $appointment);
    }

    private function ownsAppointment(User $user, Appointment $appointment): bool
    {
        return $user->isClient()
            && $appointment->client_id !== null
            && (int) $appointment->client_id === (int) $user->id;
    }
}
