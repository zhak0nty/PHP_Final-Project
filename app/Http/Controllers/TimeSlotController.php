<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeSlotRequest;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        return TimeSlot::with('doctor.user')->paginate();
    }

    public function store(StoreTimeSlotRequest $request)
    {
        $slot = TimeSlot::create($request->validated());

        return response()->json($slot->load('doctor.user'), 201);
    }

    public function show(TimeSlot $timeSlot)
    {
        return $timeSlot->load('doctor.user');
    }

    public function update(StoreTimeSlotRequest $request, TimeSlot $timeSlot)
    {
        $timeSlot->update($request->validated());

        return $timeSlot->load('doctor.user');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        return response()->json([], 204);
    }
}

