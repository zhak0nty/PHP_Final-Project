@extends('layouts.app')

@section('title', 'Specialists — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Specialists</h1>
            <p class="mt-2 text-gray-600">Our doctors and areas of focus. Booking is available without registration.</p>
        </div>

        @if ($doctors->isEmpty())
            <p class="text-gray-500">No specialists in the database yet.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($doctors as $doctor)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-[#6B21A8]/30 transition-all">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8] mb-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $doctor->user->name ?? 'Doctor' }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $doctor->specialization ?? 'General practice' }}</p>
                        @if ($doctor->services->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                @foreach ($doctor->services as $service)
                                    <span class="inline-flex rounded-lg bg-[#6B21A8]/10 px-2 py-0.5 text-xs font-medium text-[#6B21A8]">{{ $service->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ route('guest.booking.form') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[#6B21A8] hover:underline">
                            Book
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('guest.booking.form') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                Book a specialist
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
@endsection
