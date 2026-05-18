<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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

    public function delete(User $user, Appointment $appointment): bool|Response
    {
        if (! $this->ownsAppointment($user, $appointment)) {
            return false;
        }

        $appointment->loadMissing('timeSlot');

        if (! $appointment->canBeDeletedByClient()) {
            return Response::deny(
                'You can only delete a scheduled appointment at least '
                .Appointment::CLIENT_DELETE_MIN_DAYS_BEFORE
                .' days before the visit.'
            );
        }

        return true;
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
