<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\View\View;

class SpecialistController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::with('user', 'services')
            ->orderBy('id')
            ->get();

        return view('specialists.index', compact('doctors'));
    }
}
