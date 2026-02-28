@extends('layouts.app')

@section('title', ($page['title'] ?? 'Информация') . ' — MedBooking')

@section('content')
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr),minmax(0,1fr)] items-start">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#6B21A8]/10 px-3 py-1 text-xs font-medium text-[#6B21A8]">
                        Информация
                    </div>
                    <h1 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                        {{ $page['title'] ?? 'Раздел' }}
                    </h1>
                    <p class="mt-3 text-gray-600 text-base sm:text-lg max-w-2xl">
                        {{ $page['lead'] ?? '' }}
                    </p>

                    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Что это такое</h2>
                        <div class="mt-3 space-y-2 text-sm text-gray-600 leading-relaxed">
                            @foreach (($page['about'] ?? []) as $p)
                                <p>{{ $p }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>

                <aside class="rounded-2xl border border-gray-200 bg-gray-50/80 p-6 lg:sticky lg:top-24">
                    <div class="text-sm font-semibold text-gray-900">Запись</div>
                    <p class="mt-2 text-sm text-gray-600">
                        Можно записаться без регистрации: укажете имя, почту и телефон — и всё.
                    </p>
                    <a href="{{ route('guest.booking.form') }}"
                       class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                        Записаться
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('specialists.index') }}"
                       class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Смотреть специалистов
                    </a>

                    @if (!empty($page['cta']))
                        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $page['cta']['title'] }}</div>
                            <div class="mt-1 text-sm text-gray-600">{{ $page['cta']['text'] }}</div>
                        </div>
                    @endif
                </aside>
            </div>

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Когда сюда обращаются</h2>
                    <ul class="mt-4 space-y-2 text-sm text-gray-700">
                        @foreach (($page['cases'] ?? []) as $item)
                            <li class="flex gap-2">
                                <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-[#6B21A8]"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Чем пользуемся</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach (($page['equipment'] ?? []) as $eq)
                            <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $eq['title'] ?? '' }}</div>
                                <div class="mt-1 text-sm text-gray-600">{{ $eq['text'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            @if ($slug === 'narrow-directions' && !empty($specializations) && $specializations->isNotEmpty())
                <section class="mt-10 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Специальности</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($specializations as $spec)
                            <span class="inline-flex rounded-lg bg-[#6B21A8]/10 px-2.5 py-1 text-xs font-medium text-[#6B21A8]">{{ $spec }}</span>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        Перейдите в раздел специалистов, чтобы выбрать врача по специальности и записаться.
                    </p>
                    <a href="{{ route('specialists.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[#6B21A8] hover:underline">
                        Открыть специалистов →
                    </a>
                </section>
            @endif

            <section class="mt-10">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Специалисты</h2>
                        <p class="mt-1 text-sm text-gray-600">Выберите врача и запишитесь на удобное время.</p>
                    </div>
                    <a href="{{ route('specialists.index') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-[#6B21A8] hover:underline">
                        Все специалисты →
                    </a>
                </div>

                @if ($doctors->isEmpty())
                    <p class="mt-4 text-gray-500">Пока нет специалистов по этому направлению.</p>
                @else
                    <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($doctors->take(9) as $doctor)
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-[#6B21A8]/30 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">{{ $doctor->user->name }}</div>
                                        <div class="mt-1 text-sm text-gray-600">{{ $doctor->specialization ?? 'Общий приём' }}</div>
                                    </div>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#6B21A8]/10 text-[#6B21A8]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                </div>
                                @if ($doctor->services->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        @foreach ($doctor->services->take(3) as $service)
                                            <span class="inline-flex rounded-lg bg-[#6B21A8]/10 px-2 py-0.5 text-xs font-medium text-[#6B21A8]">{{ $service->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <a href="{{ route('guest.booking.form') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[#6B21A8] hover:underline">
                                    Записаться →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 text-center sm:hidden">
                    <a href="{{ route('specialists.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#6B21A8] hover:underline">
                        Все специалисты →
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection

