@extends('layouts.app')

@section('title', 'Запись без регистрации — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Запись без регистрации</h1>
            <p class="mt-2 text-gray-600">Укажите имя, email и телефон, выберите врача и время. Запись сохранится на 5 минут — если зарегистрируетесь с этим email, она появится в личном кабинете.</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,3fr)] items-start">
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Данные для записи</h2>
                    <form method="POST" action="{{ route('guest.booking.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1.5">Имя</label>
                            <input id="guest_name" name="guest_name" type="text" value="{{ old('guest_name') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="Иван Иванов">
                        </div>
                        <div>
                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input id="guest_email" name="guest_email" type="email" value="{{ old('guest_email') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="ivan@example.com">
                        </div>
                        <div>
                            <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Номер телефона</label>
                            <input id="guest_phone" name="guest_phone" type="text" value="{{ old('guest_phone') }}" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                                placeholder="+7 (999) 123-45-67">
                        </div>
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1.5">Врач</label>
                            <select id="doctor_id" name="doctor_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
                                <option value="">Выберите врача…</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                        {{ $doctor->user->name }} — {{ $doctor->specialization ?? 'Общий приём' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1.5">Услуга</label>
                            <select id="service_id" name="service_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
                                <option value="">Сначала выберите врача</option>
                                @foreach ($doctors as $doctor)
                                    @foreach ($doctor->services as $service)
                                        <option value="{{ $service->id }}" data-doctor-id="{{ $doctor->id }}" @selected(old('service_id') == $service->id)>
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="time_slot_id" class="block text-sm font-medium text-gray-700 mb-1.5">Время</label>
                            <select id="time_slot_id" name="time_slot_id" required
                                class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors bg-white">
                                <option value="">Сначала выберите врача</option>
                                @foreach ($doctors as $doctor)
                                    @foreach ($doctor->futureTimeSlots as $slot)
                                        <option value="{{ $slot->id }}" data-doctor-id="{{ $doctor->id }}" @selected(old('time_slot_id') == $slot->id)>
                                            {{ $slot->starts_at->format('d.m.Y H:i') }}–{{ $slot->ends_at->format('H:i') }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A]">
                            Записаться
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Врачи</h2>
                    <div class="space-y-3 max-h-[320px] overflow-y-auto">
                        @foreach ($doctors as $doctor)
                            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                                <div class="font-medium text-gray-900">{{ $doctor->user->name }}</div>
                                <div class="text-sm text-gray-500 mt-0.5">{{ $doctor->specialization ?? 'Общий приём' }}</div>
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

            <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Как это работает</h2>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#6B21A8]/10 text-[#6B21A8] font-medium">1</span>
                        Заполните форму — регистрация не нужна.
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#6B21A8]/10 text-[#6B21A8] font-medium">2</span>
                        Запись сохраняется на <strong>5 минут</strong>. За это время выбранное время приёма удерживается за вами.
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#6B21A8]/10 text-[#6B21A8] font-medium">3</span>
                        Если вы <strong>зарегистрируетесь</strong> с тем же email — запись появится в личном кабинете и останется навсегда.
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl border-2 border-[#6B21A8] bg-white px-4 py-2.5 text-sm font-medium text-[#6B21A8] hover:bg-[#6B21A8]/5 transition-colors">
                    Уже есть аккаунт? Войти
                </a>
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

                servicePlaceholder.textContent = doctorId ? 'Выберите услугу…' : 'Сначала выберите врача';
                timePlaceholder.textContent = doctorId ? 'Выберите время…' : 'Сначала выберите врача';

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
