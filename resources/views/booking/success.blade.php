@extends('layouts.app')

@section('title', 'Запись создана — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="max-w-md mx-auto">
            <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-[0_4px_24px_rgba(0,0,0,0.06)] text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#6B21A8]/10 text-[#6B21A8] mb-6">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Ваша запись успешно создана
                </h1>
                <p class="mt-3 text-gray-600">
                    Мы уведомили врача. Ожидайте подтверждения при необходимости.
                </p>
                @if (!empty($appointment['datetime']) || !empty($appointment['doctor']) || !empty($appointment['service']))
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50/80 px-5 py-4 text-left text-sm">
                        @if (!empty($appointment['datetime']))
                            <div class="flex justify-between gap-2"><span class="text-gray-500">Дата и время</span><span class="font-medium text-gray-900">{{ $appointment['datetime'] }}</span></div>
                        @endif
                        @if (!empty($appointment['doctor']))
                            <div class="flex justify-between gap-2 mt-2"><span class="text-gray-500">Врач</span><span class="font-medium text-gray-900">{{ $appointment['doctor'] }}</span></div>
                        @endif
                        @if (!empty($appointment['service']))
                            <div class="flex justify-between gap-2 mt-2"><span class="text-gray-500">Услуга</span><span class="font-medium text-gray-900">{{ $appointment['service'] }}</span></div>
                        @endif
                    </div>
                @endif
                @if (!empty($guest) && $guest)
                    <p class="mt-4 text-sm text-gray-600">
                        Зарегистрируйтесь с этим email в течение 5 минут — запись появится в личном кабинете.
                    </p>
                    <a href="{{ route('register', ['email' => $appointment['guest_email'] ?? '']) }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-5 py-3.5 text-base font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                        Зарегистрироваться
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('home') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-gray-300 bg-white px-5 py-3 text-base font-medium text-gray-700 hover:bg-gray-50">
                        На главную
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-5 py-3.5 text-base font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                        В главное меню
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
