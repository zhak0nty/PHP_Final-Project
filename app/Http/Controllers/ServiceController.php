<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::paginate();
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());

        return response()->json($service, 201);
    }

    public function show(Service $service)
    {
        return $service->load('doctors');
    }

    public function update(StoreServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return $service;
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([], 204);
    }
}

