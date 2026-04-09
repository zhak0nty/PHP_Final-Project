<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index()
    {
        return DoctorResource::collection(Doctor::with('user')->paginate());
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->validated());

        return (new DoctorResource($doctor->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Doctor $doctor)
    {
        return new DoctorResource($doctor->load('user', 'services'));
    }

    public function update(StoreDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        return new DoctorResource($doctor->load('user'));
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([], 204);
    }
}
