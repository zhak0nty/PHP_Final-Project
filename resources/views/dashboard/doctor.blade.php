@extends('layouts.app')

@section('title', 'Your appointments — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Your appointments</h1>
            <p class="mt-2 text-gray-600">Current client bookings.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            @if (! $doctor)
                <p class="text-gray-500">No doctor profile exists for this user yet.</p>
            @elseif ($appointments->isEmpty())
                <p class="text-gray-500">No appointments yet.</p>
            @else
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Date & time</th>
                                <th class="px-4 py-3">Client</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ optional($appointment->timeSlot?->starts_at)->format('d.m.Y H:i') }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $appointment->client?->name ?? $appointment->guest_name }}
                                        @if ($appointment->isGuest())
                                            <span class="ml-1 text-xs text-gray-500">(guest)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $appointment->service?->name }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $status = $appointment->status;
                                            $badge = [
                                                'scheduled' => 'bg-emerald-50 text-emerald-700',
                                                'cancelled' => 'bg-red-50 text-red-700',
                                                'completed' => 'bg-gray-100 text-gray-700',
                                            ][$status] ?? 'bg-gray-100 text-gray-700';
                                            $statusLabel = match ($status) {
                                                'scheduled' => 'Scheduled',
                                                'cancelled' => 'Cancelled',
                                                'completed' => 'Completed',
                                                default => $status,
                                            };
                                        @endphp
                                        <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {{ $badge }}">{{ $statusLabel }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
