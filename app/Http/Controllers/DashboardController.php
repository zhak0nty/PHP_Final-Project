<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Services\TimeSlotGenerator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $doctors = Doctor::with('user')->get();
            $services = Service::withCount('appointments')->get();
            $timeSlots = TimeSlot::with('doctor.user')->get();

            return view('dashboard.admin', compact('user', 'doctors', 'services', 'timeSlots'));
        }

        if ($user->isDoctor()) {
            $doctor = $user->doctor;

            $appointments = Appointment::with('client', 'service', 'timeSlot')
                ->where('doctor_id', $doctor?->id)
                ->where(function ($q) {
                    $q->whereNotNull('client_id')
                        ->orWhere(function ($q2) {
                            $q2->whereNull('client_id')->where(function ($q3) {
                                $q3->whereNull('expires_at')->orWhere('expires_at', '>', now());
                            });
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.doctor', compact('user', 'doctor', 'appointments'));
        }

        // Client dashboard
        TimeSlotGenerator::ensureUpcomingSlotsExist();

        $doctors = Doctor::with('user', 'services', 'futureTimeSlots')->get();
        $appointments = $user->clientAppointments()
            ->with('doctor.user', 'service', 'timeSlot')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.client', compact('user', 'doctors', 'appointments'));
    }
}
