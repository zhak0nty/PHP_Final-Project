<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService
    ) {
    }

    public function indexForClient(Request $request)
    {
        $appointments = Appointment::with('doctor.user', 'service', 'timeSlot')
            ->where('client_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate();

        return $appointments;
    }

    public function indexForDoctor(Request $request)
    {
        $doctor = $request->user()->doctor;

        $appointments = Appointment::with('client', 'service', 'timeSlot')
            ->where('doctor_id', $doctor?->id)
            ->orderByDesc('created_at')
            ->paginate();

        return $appointments;
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->createAppointment(
            $request->user(),
            $request->validated()
        );

        return response()->json($appointment->load('doctor.user', 'service', 'timeSlot'), 201);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
        ]);

        return $appointment->load('doctor.user', 'service', 'timeSlot');
    }
}

