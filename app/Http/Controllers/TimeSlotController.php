<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeSlotRequest;
use App\Http\Resources\TimeSlotResource;
use App\Models\TimeSlot;

class TimeSlotController extends Controller
{
    public function index()
    {
        return TimeSlotResource::collection(TimeSlot::with('doctor.user')->paginate());
    }

    public function store(StoreTimeSlotRequest $request)
    {
        $slot = TimeSlot::create($request->validated());

        return (new TimeSlotResource($slot->load('doctor.user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(TimeSlot $timeSlot)
    {
        return new TimeSlotResource($timeSlot->load('doctor.user'));
    }

    public function update(StoreTimeSlotRequest $request, TimeSlot $timeSlot)
    {
        $timeSlot->update($request->validated());

        return new TimeSlotResource($timeSlot->load('doctor.user'));
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        return response()->json([], 204);
    }
}
