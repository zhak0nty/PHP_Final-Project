@extends('layouts.app')

@section('title', 'Book without registration — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Book without registration</h1>
            <p class="mt-2 text-gray-600">Enter your name, email, and phone, then choose a doctor and time. The booking is held for 5 minutes — if you register with the same email, it will appear in your account.</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,1fr)] items-start">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('guest.booking.store') }}" class="space-y-5">
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
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1.5">Doctor</label>
                            <select id="doctor_id" name="doctor_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
                                <option value="">Choose a doctor…</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                        {{ $doctor->user->name }} — {{ $doctor->specialization ?? 'General practice' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1.5">Service</label>
                            <select id="service_id" name="service_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
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
                        <div class="sm:col-span-2">
                            <label for="time_slot_id" class="block text-sm font-medium text-gray-700 mb-1.5">Time</label>
                            <select id="time_slot_id" name="time_slot_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
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
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] focus:outline-none focus:ring-2 focus:ring-[#6B21A8] focus:ring-offset-2 transition-colors">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var doctorSelect = document.getElementById('doctor_id');
            var serviceSelect = document.getElementById('service_id');
            var timeSelect = document.getElementById('time_slot_id');

            function filterByDoctor() {
                var doctorId = doctorSelect.value;
                var servicePlaceholder = serviceSelect.querySelector('option[value=""]');
                var timePlaceholder = timeSelect.querySelector('option[value=""]');

                servicePlaceholder.textContent = doctorId ? 'Choose a service…' : 'Select a doctor first';
                timePlaceholder.textContent = doctorId ? 'Choose a time…' : 'Select a doctor first';

                [].forEach.call(serviceSelect.options, function (opt) {
                    if (opt.value === '') return;
                    opt.disabled = doctorId !== '' && opt.getAttribute('data-doctor-id') !== doctorId;
                    opt.hidden = opt.disabled;
                });
                [].forEach.call(timeSelect.options, function (opt) {
                    if (opt.value === '') return;
                    opt.disabled = doctorId !== '' && opt.getAttribute('data-doctor-id') !== doctorId;
                    opt.hidden = opt.disabled;
                });

                if (!doctorId) {
                    serviceSelect.value = '';
                    timeSelect.value = '';
                } else {
                    if (serviceSelect.value && serviceSelect.querySelector('option[value="' + serviceSelect.value + '"]')?.disabled) serviceSelect.value = '';
                    if (timeSelect.value && timeSelect.querySelector('option[value="' + timeSelect.value + '"]')?.disabled) timeSelect.value = '';
                }
            }

            doctorSelect.addEventListener('change', function () {
                serviceSelect.value = '';
                timeSelect.value = '';
                filterByDoctor();
            });

            filterByDoctor();
        });
    </script>
@endsection
