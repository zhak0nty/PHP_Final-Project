<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientAppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService
    ) {
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = $this->appointmentService->createAppointment(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('client.booking.success')
            ->with('appointment_created', [
                'datetime' => $appointment->timeSlot?->starts_at?->format('d.m.Y H:i'),
                'doctor'   => $appointment->doctor?->user?->name,
                'service'  => $appointment->service?->name,
            ]);
    }

    public function destroy(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('view', $appointment);

        $appointment->loadMissing('timeSlot');

        if (! $appointment->canBeDeletedByClient()) {
            return redirect()
                ->route('dashboard')
                ->withErrors([
                    'appointment' => 'You can only delete a scheduled appointment at least '
                        .Appointment::CLIENT_DELETE_MIN_DAYS_BEFORE
                        .' days before the visit.',
                ]);
        }

        $appointment->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Appointment deleted.');
    }
}

