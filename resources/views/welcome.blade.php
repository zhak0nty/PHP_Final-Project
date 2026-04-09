@extends('layouts.app')

@section('title', 'MedBooking — Book a doctor')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight leading-tight">
                        Care for you <span class="text-[#6B21A8]">at every step</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 max-w-xl">
                        Modern doctor booking: choose a specialist, a service, and a convenient time. Fast, simple, and reliable.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('guest.booking.form') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-6 py-3.5 text-base font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                            Book without signing up
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border-2 border-gray-300 bg-white px-6 py-3.5 text-base font-medium text-gray-800 hover:border-[#6B21A8] hover:text-[#6B21A8] transition-colors">
                            Log in to account
                        </a>
                        <a href="{{ route('home') }}#services" class="inline-flex items-center rounded-xl border-2 border-gray-300 bg-white px-6 py-3.5 text-base font-medium text-gray-800 hover:border-[#6B21A8] hover:text-[#6B21A8] transition-colors">
                            Services
                        </a>
                    </div>
                    <div class="mt-10 grid sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <div class="font-semibold text-gray-900">Flexible schedule</div>
                                <p class="text-sm text-gray-600 mt-0.5">Book several days ahead</p>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <div>
                                <div class="font-semibold text-gray-900">Trusted doctors</div>
                                <p class="text-sm text-gray-600 mt-0.5">Many specialties and services</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative hidden lg:flex lg:justify-center lg:items-start">
                    <div class="w-full max-w-[340px]">
                        <div class="relative rounded-3xl overflow-hidden shadow-xl ring-2 ring-[#6B21A8]/30 bg-gray-100" style="aspect-ratio: 3/4;">
                            <img
                                src="{{ asset('images/doctor-hero.png') }}"
                                alt="MedBooking doctor"
                                class="absolute inset-0 h-full w-full object-cover"
                                style="object-position: 50% 25%;"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#6B21A8]/70 via-transparent to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <div class="rounded-xl bg-white/95 backdrop-blur-sm px-3 py-2.5 text-gray-800 shadow-lg border border-gray-200/80">
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-[#6B21A8]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Online booking
                                    </div>
                                    <p class="text-[11px] text-gray-600 mt-0.5 leading-snug">Pick a specialist and a time. You can book without registering — your data is protected.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="bg-gray-50/50 py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <p class="text-sm font-medium text-[#6B21A8]">Services</p>
                    <h2 class="mt-1 text-2xl lg:text-3xl font-bold text-gray-900">Areas and specialties</h2>
                    <p class="mt-2 text-gray-600 max-w-2xl">Consultations, diagnostics, and treatment. Choose a doctor and a convenient time.</p>
                </div>
                <a href="{{ route('specialists.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#5B1B8A] shrink-0">
                    All specialists
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach (['Consultation', 'Diagnostics', 'Therapy', 'Specialists', 'Narrow specialties', 'Online booking'] as $title)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-[#6B21A8]/30 transition-all">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8] mb-4">
                        @switch($title)
                            @case('Consultation')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h6M7 12h3M5 20l3-3h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2z"/>
                                </svg>
                                @break
                            @case('Diagnostics')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 4a6.5 6.5 0 1 0 4.597 11.103L20 20.5 18.5 22l-4.89-4.89A6.5 6.5 0 0 0 10.5 4z"/>
                                </svg>
                                @break
                            @case('Therapy')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4v4h4v4h-4v4h-4v-4H6v-4h4V4z"/>
                                </svg>
                                @break
                            @case('Specialists')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM4 20a4 4 0 0 1 8 0v1H4v-1zm8 1v-1a4 4 0 0 1 7-2.646M3 21h18"/>
                                </svg>
                                @break
                            @case('Narrow specialties')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l2.09 4.24L19 8l-3.5 3.4L16.18 17 12 14.9 7.82 17 8.5 11.4 5 8l4.91-.76L12 3z"/>
                                </svg>
                                @break
                            @case('Online booking')
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v2m10-2v2M5 8h14M6 6h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm3 6h4"/>
                                </svg>
                                @break
                            @default
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16z"/>
                                </svg>
                        @endswitch
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-gray-600">Learn more about this area and how to book a specialist.</p>
                    @php
                        $href = match ($title) {
                            'Consultation' => route('info.show', ['slug' => 'consultation']),
                            'Diagnostics' => route('info.show', ['slug' => 'diagnostics']),
                            'Therapy' => route('info.show', ['slug' => 'therapy']),
                            'Specialists' => route('specialists.index'),
                            'Narrow specialties' => route('info.show', ['slug' => 'narrow-directions']),
                            'Online booking' => route('info.show', ['slug' => 'online-booking']),
                            default => route('specialists.index'),
                        };
                    @endphp
                    <a href="{{ $href }}" class="mt-4 inline-flex items-center text-sm font-medium text-[#6B21A8] hover:underline">Learn more →</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="doctors" class="py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8 items-center">
                <div class="space-y-6">
                    <div class="rounded-2xl bg-[#6B21A8] p-6 text-white">
                        <div class="text-3xl font-bold">98%</div>
                        <div class="mt-1 font-medium">Satisfied patients</div>
                        <p class="mt-2 text-sm text-white/80">Easy booking and attention to everyone.</p>
                    </div>
                    <div class="rounded-2xl bg-[#6B21A8] p-6 text-white">
                        <div class="text-3xl font-bold">5000+</div>
                        <div class="mt-1 font-medium">Successful bookings</div>
                        <p class="mt-2 text-sm text-white/80">Online scheduling without queues.</p>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <p class="text-sm font-medium text-[#6B21A8]">About us</p>
                    <h2 class="mt-1 text-2xl lg:text-3xl font-bold text-gray-900">Care. Expertise. Quality.</h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        MedBooking is a simple way to book a doctor or practitioner: admins set up doctors and schedules, clients pick a time in a few clicks. Doctors see their appointments; clients see theirs. Everything in one place.
                    </p>
                    <a href="{{ route('register') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#5B1B8A]">
                        Book an appointment
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
