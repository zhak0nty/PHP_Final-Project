@extends('layouts.app')

@section('title', 'Book without registration — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Book without registration</h1>
            <p class="mt-2 text-gray-600">Enter your name, email, and phone, then choose a doctor and time. The booking is held for 5 minutes — if you register with the same email, it will appear in your account.</p>
        </div>

        @if ($doctors->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-8 text-center max-w-xl mx-auto">
                <p class="text-amber-950 font-medium">No specialists available for booking yet.</p>
                <p class="mt-2 text-sm text-amber-900/80">Please try again later or <a href="{{ route('home') }}#services" class="font-semibold text-[#6B21A8] hover:underline">browse services</a>.</p>
            </div>
        @else
        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,1fr)] items-start">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('guest.booking.store') }}" class="space-y-5 rounded-2xl border border-[#E9DDF5] bg-gradient-to-b from-[#FCFAFF] to-white p-5">
                    @csrf
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking details</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                            <input id="guest_name" name="guest_name" type="text" value="{{ old('guest_name') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="Jane Doe">
                        </div>
                        <div>
                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input id="guest_email" name="guest_email" type="email" value="{{ old('guest_email') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="you@example.com">
                        </div>
                        <div>
                            <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone number</label>
                            <input id="guest_phone" name="guest_phone" type="text" value="{{ old('guest_phone') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="+1 555 000 0000">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="doctor_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Doctor</label>
                            <div class="group relative">
                                <select id="doctor_id" name="doctor_id" required
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
                            <p class="mt-1.5 text-xs text-gray-500">Choose a specialist to see relevant services and time.</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="service_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Service</label>
                            <div class="group relative">
                                <select id="service_id" name="service_id" required data-old="{{ old('service_id') }}"
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
                            <p class="mt-1.5 text-xs text-gray-500">The list updates automatically after selecting a doctor.</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="time_slot_id" class="mb-1.5 block text-sm font-semibold text-gray-800">Time</label>
                            <div class="group relative">
                                <select id="time_slot_id" name="time_slot_id" required data-old="{{ old('time_slot_id') }}"
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
                            <p class="mt-1.5 text-xs text-gray-500">Only future and available slots are displayed.</p>
                        </div>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-[#6B21A8] px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#5B1B8A] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#6B21A8] focus:ring-offset-2">
                        Book appointment
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Doctors</h2>
                    <div class="space-y-3 max-h-[360px] overflow-y-auto">
                        @foreach ($doctors as $doctor)
                            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                                <div class="font-medium text-gray-900">{{ $doctor->user->name }}</div>
                                <div class="text-sm text-gray-500 mt-0.5">{{ $doctor->specialization ?? 'General practice' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">How it works</h2>
                <ol class="space-y-3 text-sm text-gray-600 list-decimal list-inside">
                    <li>
                        Fill out the form — no account required.
                    </li>
                    <li>
                        The booking is held for <strong>5 minutes</strong>. During that time the slot stays reserved for you.
                    </li>
                    <li>
                        If you <strong>register</strong> with the same email — the booking appears in your account and stays there.
                    </li>
                </ol>
                <p class="mt-4 text-sm text-gray-600">
                    Already have an account? <a href="{{ route('login') }}" class="font-medium text-[#6B21A8] hover:underline">Log in</a>
                </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    @unless($doctors->isEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var doctorSelect = document.getElementById('doctor_id');
            var serviceSelect = document.getElementById('service_id');
            var timeSelect = document.getElementById('time_slot_id');
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
    @endunless
@endsection
