@extends('layouts.app')

@section('title', 'MedBooking — Запись к врачу')

@section('content')
    {{-- Hero как в CUREVIO --}}
    <section class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight leading-tight">
                        Забота о вас <span class="text-[#6B21A8]">на каждом шаге</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 max-w-xl">
                        Современная запись к врачу: выберите специалиста, услугу и удобное время. Быстро, просто и надёжно.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('guest.booking.form') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-6 py-3.5 text-base font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                            Записаться без регистрации
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border-2 border-gray-300 bg-white px-6 py-3.5 text-base font-medium text-gray-800 hover:border-[#6B21A8] hover:text-[#6B21A8] transition-colors">
                            Войти в кабинет
                        </a>
                        <a href="{{ route('home') }}#services" class="inline-flex items-center rounded-xl border-2 border-gray-300 bg-white px-6 py-3.5 text-base font-medium text-gray-800 hover:border-[#6B21A8] hover:text-[#6B21A8] transition-colors">
                            Услуги
                        </a>
                    </div>
                    {{-- Две мини-карточки как "24/7 Emergency" / "Expert Medical Team" --}}
                    <div class="mt-10 grid sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <div class="font-semibold text-gray-900">Удобное расписание</div>
                                <p class="text-sm text-gray-600 mt-0.5">Слоты на несколько дней вперёд</p>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <div>
                                <div class="font-semibold text-gray-900">Проверенные врачи</div>
                                <p class="text-sm text-gray-600 mt-0.5">Разные специальности и услуги</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Правая часть: фото врача в компактной рамке --}}
                <div class="relative hidden lg:flex lg:justify-center lg:items-start">
                    <div class="w-full max-w-[340px]">
                        <div class="relative rounded-3xl overflow-hidden shadow-xl ring-2 ring-[#6B21A8]/30 bg-gray-100" style="aspect-ratio: 3/4;">
                            <img
                                src="{{ asset('images/doctor-hero.png') }}"
                                alt="Врач MedBooking"
                                class="absolute inset-0 h-full w-full object-cover"
                                style="object-position: 50% 25%;"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-[#6B21A8]/70 via-transparent to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <div class="rounded-xl bg-white/95 backdrop-blur-sm px-3 py-2.5 text-gray-800 shadow-lg border border-gray-200/80">
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-[#6B21A8]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Демо-аккаунты
                                    </div>
                                    <p class="text-[11px] text-gray-600 mt-0.5">admin · doctor1 · client@example.com</p>
                                    <p class="text-[11px] text-gray-500">пароль: password</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Секция "Направления / Услуги" — карточки как Departments --}}
    <section id="services" class="bg-gray-50/50 py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <p class="text-sm font-medium text-[#6B21A8]">Услуги</p>
                    <h2 class="mt-1 text-2xl lg:text-3xl font-bold text-gray-900">Направления и специализации</h2>
                    <p class="mt-2 text-gray-600 max-w-2xl">Консультации, диагностика и лечение. Выберите врача и удобное время.</p>
                </div>
                <a href="{{ route('specialists.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#5B1B8A] shrink-0">
                    Все специалисты
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach (['Консультация', 'Диагностика', 'Терапия', 'Специалисты', 'Узкие направления', 'Запись онлайн'] as $title)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-[#6B21A8]/30 transition-all">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8] mb-4">
                        @switch($title)
                            @case('Консультация')
                                {{-- Диалог / консультация --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h6M7 12h3M5 20l3-3h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2z"/>
                                </svg>
                                @break
                            @case('Диагностика')
                                {{-- Лупа / поиск причины --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 4a6.5 6.5 0 1 0 4.597 11.103L20 20.5 18.5 22l-4.89-4.89A6.5 6.5 0 0 0 10.5 4z"/>
                                </svg>
                                @break
                            @case('Терапия')
                                {{-- Медицинский крест --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4v4h4v4h-4v4h-4v-4H6v-4h4V4z"/>
                                </svg>
                                @break
                            @case('Специалисты')
                                {{-- Группа людей --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM4 20a4 4 0 0 1 8 0v1H4v-1zm8 1v-1a4 4 0 0 1 7-2.646M3 21h18"/>
                                </svg>
                                @break
                            @case('Узкие направления')
                                {{-- Звёздочка / особые направления --}}
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l2.09 4.24L19 8l-3.5 3.4L16.18 17 12 14.9 7.82 17 8.5 11.4 5 8l4.91-.76L12 3z"/>
                                </svg>
                                @break
                            @case('Запись онлайн')
                                {{-- Календарь / запись --}}
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
                    <p class="mt-2 text-sm text-gray-600">Подробное описание направления и возможностей записи к специалистам.</p>
                    @php
                        $href = match ($title) {
                            'Консультация' => route('info.show', ['slug' => 'consultation']),
                            'Диагностика' => route('info.show', ['slug' => 'diagnostics']),
                            'Терапия' => route('info.show', ['slug' => 'therapy']),
                            'Специалисты' => route('specialists.index'),
                            'Узкие направления' => route('info.show', ['slug' => 'narrow-directions']),
                            'Запись онлайн' => route('info.show', ['slug' => 'online-booking']),
                            default => route('specialists.index'),
                        };
                    @endphp
                    <a href="{{ $href }}" class="mt-4 inline-flex items-center text-sm font-medium text-[#6B21A8] hover:underline">Подробнее →</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Блок "О нас" / статистика — две фиолетовые карточки как 98% / 50,000+ --}}
    <section id="doctors" class="py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8 items-center">
                <div class="space-y-6">
                    <div class="rounded-2xl bg-[#6B21A8] p-6 text-white">
                        <div class="text-3xl font-bold">98%</div>
                        <div class="mt-1 font-medium">Довольных пациентов</div>
                        <p class="mt-2 text-sm text-white/80">Удобная запись и внимание к каждому.</p>
                    </div>
                    <div class="rounded-2xl bg-[#6B21A8] p-6 text-white">
                        <div class="text-3xl font-bold">5000+</div>
                        <div class="mt-1 font-medium">Успешных записей</div>
                        <p class="mt-2 text-sm text-white/80">Онлайн-бронирование без очередей.</p>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <p class="text-sm font-medium text-[#6B21A8]">О нас</p>
                    <h2 class="mt-1 text-2xl lg:text-3xl font-bold text-gray-900">Забота. Экспертиза. Качество.</h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        MedBooking — это простая система записи к врачу или мастеру: администратор настраивает врачей и расписание, клиенты выбирают слот и записываются в пару кликов. Врач видит свои приёмы, клиент — свои записи. Всё в одном месте.
                    </p>
                    <a href="{{ route('register') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#5B1B8A]">
                        Записаться на приём
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
