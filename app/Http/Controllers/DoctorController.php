<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return Doctor::with('user')->paginate();
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = Doctor::create($request->validated());

        return response()->json($doctor->load('user'), 201);
    }

    public function show(Doctor $doctor)
    {
        return $doctor->load('user', 'services');
    }

    public function update(StoreDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());

        return $doctor->load('user');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([], 204);
    }
}

