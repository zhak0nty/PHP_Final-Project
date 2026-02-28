@extends('layouts.app')

@section('title', 'Админ-панель — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Админ-панель</h1>
            <p class="mt-2 text-gray-600">Обзор врачей, услуг и слотов. CRUD доступен через API.</p>
        </div>
        <div class="grid gap-6 lg:grid-cols-3 items-start">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Врачи</h2>
                <div class="space-y-3 max-h-[360px] overflow-y-auto">
                    @foreach ($doctors as $doctor)
                        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                            <div class="font-medium text-gray-900">{{ $doctor->user->name }}</div>
                            <div class="text-sm text-gray-500 mt-0.5">{{ $doctor->specialization ?? 'Общий приём' }}</div>
                            <div class="text-xs text-gray-400 mt-1">{{ $doctor->user->email }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Услуги</h2>
                <div class="space-y-3 max-h-[360px] overflow-y-auto">
                    @foreach ($services as $service)
                        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                            <div class="text-sm text-gray-500 mt-0.5">{{ $service->duration_minutes }} мин.</div>
                            <span class="inline-flex mt-2 rounded-lg bg-[#6B21A8]/10 px-2 py-0.5 text-xs font-medium text-[#6B21A8]">Записей: {{ $service->appointments_count ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Слоты расписания</h2>
                <div class="space-y-3 max-h-[360px] overflow-y-auto">
                    @foreach ($timeSlots as $slot)
                        <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900">{{ $slot->doctor->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $slot->starts_at->format('d.m.Y H:i') }}–{{ $slot->ends_at->format('H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
