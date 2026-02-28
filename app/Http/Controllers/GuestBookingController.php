<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestAppointmentRequest;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuestBookingController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService
    ) {
    }

    public function showForm(): View
    {
        $doctors = Doctor::with('user', 'services', 'timeSlots')->get();

        return view('booking.guest-form', compact('doctors'));
    }

    public function store(StoreGuestAppointmentRequest $request): RedirectResponse
    {
        $appointment = $this->appointmentService->createGuestAppointment($request->validated());

        return redirect()
            ->route('guest.booking.success')
            ->with('appointment_created', [
                'datetime' => $appointment->timeSlot?->starts_at?->format('d.m.Y H:i'),
                'doctor' => $appointment->doctor?->user?->name,
                'service' => $appointment->service?->name,
                'guest_email' => $appointment->guest_email,
            ]);
    }
}
