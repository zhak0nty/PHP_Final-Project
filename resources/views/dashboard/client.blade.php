@extends('layouts.app')

@section('title', 'Book a doctor — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Book a doctor</h1>
            <p class="mt-2 text-gray-600">Choose a doctor, service, and a convenient time. The system validates your choices.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,3fr)] items-start">
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">New appointment</h2>
                    <form method="POST" action="{{ route('client.appointments.store') }}" class="space-y-5 rounded-2xl border border-[#E9DDF5] bg-gradient-to-b from-[#FCFAFF] to-white p-5">
                        @csrf
                        <div>
                        <label for="client_doctor_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Doctor</label>
                            <div class="group relative">
                                <select id="client_doctor_id" name="doctor_id" required
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                    class="block w-full rounded-2xl border border-[#DDC9EF] bg-white px-4 py-3 pr-4 text-sm font-medium text-gray-900 shadow-sm transition-all duration-200 hover:border-[#BC95DE] hover:shadow-md focus:border-[#6B21A8] focus:outline-none focus:ring-4 focus:ring-[#6B21A8]/15">
                                    <option value="">Choose a doctor...</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                            {{ $doctor->user->name }} — {{ $doctor->specialization ?? 'General practice' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Pick a specialist first to unlock services and time slots.</p>
                        </div>
                        <div>
                            <label for="client_service_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Service</label>
                            <div class="group relative">
                                <select id="client_service_id" name="service_id" required data-old="{{ old('service_id') }}"
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                    class="block w-full rounded-2xl border border-[#DDC9EF] bg-white px-4 py-3 pr-4 text-sm font-medium text-gray-900 shadow-sm transition-all duration-200 hover:border-[#BC95DE] hover:shadow-md focus:border-[#6B21A8] focus:outline-none focus:ring-4 focus:ring-[#6B21A8]/15">
                                    <option value="">Select a doctor first</option>
                                    @foreach ($doctors as $doctor)
                                        @foreach ($doctor->services as $service)
                                            <option value="{{ $service->id }}" data-doctor-id="{{ $doctor->id }}" @selected(old('service_id') == $service->id)>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Only services for the selected doctor are shown.</p>
                        </div>
                        <div>
                            <label for="client_time_slot_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Time</label>
                            <div class="group relative">
                                <select id="client_time_slot_id" name="time_slot_id" required data-old="{{ old('time_slot_id') }}"
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                    class="block w-full rounded-2xl border border-[#DDC9EF] bg-white px-4 py-3 pr-4 text-sm font-medium text-gray-900 shadow-sm transition-all duration-200 hover:border-[#BC95DE] hover:shadow-md focus:border-[#6B21A8] focus:outline-none focus:ring-4 focus:ring-[#6B21A8]/15">
                                    <option value="">Select a doctor first</option>
                                    @foreach ($doctors as $doctor)
                                        @foreach ($doctor->futureTimeSlots as $slot)
                                            <option value="{{ $slot->id }}" data-doctor-id="{{ $doctor->id }}" @selected(old('time_slot_id') == $slot->id)>
                                                {{ $slot->starts_at->format('d.m.Y H:i') }}–{{ $slot->ends_at->format('H:i') }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Choose a convenient date and time from available slots.</p>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-[#6B21A8] px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#5B1B8A] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#6B21A8] focus:ring-offset-2">
                            Create appointment
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Doctors</h2>
                    <div class="space-y-3 max-h-[320px] overflow-y-auto">
                        @foreach ($doctors as $doctor)
                            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                                <div class="font-medium text-gray-900">{{ $doctor->user->name }}</div>
                                <div class="text-sm text-gray-500 mt-0.5">{{ $doctor->specialization ?? 'General practice' }}</div>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @foreach ($doctor->services as $service)
                                        <span class="inline-flex rounded-lg bg-[#6B21A8]/10 px-2 py-0.5 text-xs font-medium text-[#6B21A8]">{{ $service->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">My appointments</h2>
                @if ($appointments->isEmpty())
                    <p class="text-gray-500">You have no appointments yet.</p>
                @else
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Date & time</th>
                                    <th class="px-4 py-3">Doctor</th>
                                    <th class="px-4 py-3">Service</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ optional($appointment->timeSlot?->starts_at)->format('d.m.Y H:i') }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $appointment->doctor?->user?->name }}</td>
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
                                        <td class="px-4 py-3">
                                            @if ($appointment->canBeDeletedByClient())
                                                <form method="POST" action="{{ route('client.appointments.destroy', $appointment) }}" class="inline" onsubmit="return confirm('Delete this appointment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 hover:underline">
                                                        Delete
                                                    </button>
                                                </form>
                                            @elseif ($appointment->status === \App\Models\Appointment::STATUS_SCHEDULED)
                                                <span class="text-xs text-gray-500" title="Deletion is only allowed at least {{ \App\Models\Appointment::CLIENT_DELETE_MIN_DAYS_BEFORE }} days before the visit">
                                                    Cannot delete (&lt; {{ \App\Models\Appointment::CLIENT_DELETE_MIN_DAYS_BEFORE }} days)
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var doctorSelect = document.getElementById('client_doctor_id');
            var serviceSelect = document.getElementById('client_service_id');
            var timeSelect = document.getElementById('client_time_slot_id');
            var allServiceOptions = Array.from(serviceSelect.querySelectorAll('option[data-doctor-id]')).map(function (opt) {
                return opt.cloneNode(true);
            });
            var allTimeOptions = Array.from(timeSelect.querySelectorAll('option[data-doctor-id]')).map(function (opt) {
                return opt.cloneNode(true);
            });

            function rebuildSelect(selectEl, options, doctorId, placeholderText, selectedValue) {
                selectEl.innerHTML = '';

                var placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = placeholderText;
                selectEl.appendChild(placeholder);

                if (!doctorId) {
                    selectEl.value = '';
                    return;
                }

                var matched = options.filter(function (opt) {
                    return opt.getAttribute('data-doctor-id') === doctorId;
                });

                matched.forEach(function (opt) {
                    selectEl.appendChild(opt.cloneNode(true));
                });

                if (selectedValue) {
                    var exists = Array.from(selectEl.options).some(function (opt) {
                        return opt.value === String(selectedValue);
                    });
                    selectEl.value = exists ? String(selectedValue) : '';
                } else {
                    selectEl.value = '';
                }
            }

            function filterByDoctor() {
                var doctorId = doctorSelect.value;
                var serviceSelectedValue = serviceSelect.value || serviceSelect.dataset.old || '';
                var timeSelectedValue = timeSelect.value || timeSelect.dataset.old || '';

                rebuildSelect(
                    serviceSelect,
                    allServiceOptions,
                    doctorId,
                    doctorId ? 'Choose a service…' : 'Select a doctor first',
                    serviceSelectedValue
                );
                rebuildSelect(
                    timeSelect,
                    allTimeOptions,
                    doctorId,
                    doctorId ? 'Choose a time…' : 'Select a doctor first',
                    timeSelectedValue
                );

                delete serviceSelect.dataset.old;
                delete timeSelect.dataset.old;
            }

            doctorSelect.addEventListener('change', function () {
                serviceSelect.dataset.old = '';
                timeSelect.dataset.old = '';
                filterByDoctor();
            });

            filterByDoctor();
        });
    </script>
@endsection
