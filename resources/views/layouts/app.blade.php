<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'MedBooking'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="bg-white text-gray-900 min-h-screen antialiased">
        <div class="min-h-screen flex flex-col">
            {{-- Header как в CUREVIO: логотип слева, нав по центру, CTA справа --}}
            <header class="sticky top-0 z-50 border-b border-gray-200/80 bg-white/95 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 lg:h-18 items-center justify-between">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#6B21A8] text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M11 2v2.07A7.004 7.004 0 0 0 5 10v4h2v6h8v-6h2v-4a7.004 7.004 0 0 0-6-5.93V2h-2zm0 4a3 3 0 0 1 3 3v4H8v-4a3 3 0 0 1 3-3z"/>
                                </svg>
                            </span>
                            <span class="text-xl font-bold text-gray-900">MedBooking</span>
                        </a>

                        <nav class="hidden md:flex items-center justify-center gap-8 flex-1 px-8">
                            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8] transition-colors">Главная</a>
                            <a href="{{ route('home') }}#services" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8] transition-colors">Услуги</a>
                            <a href="{{ route('specialists.index') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8] transition-colors">Врачи</a>
                            <a href="{{ route('reviews.index') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8] transition-colors">Отзывы</a>
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8] transition-colors">Запись</a>
                        </nav>

                        <div class="flex items-center gap-3 shrink-0">
                            @auth
                                <span class="hidden sm:inline text-sm text-gray-600">
                                    {{ auth()->user()->name }}
                                    <span class="text-[#6B21A8] font-medium"> · {{ auth()->user()->role }}</span>
                                </span>
                                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8]">Панель</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Выйти</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-[#6B21A8]">Войти</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] transition-colors">
                                    Записаться
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @if (session('status'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>

            <footer class="border-t border-gray-200 bg-white mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                    <span class="font-medium text-gray-700">MedBooking</span>
                    <span>Demo: admin@example.com · client@example.com · doctor1@example.com (пароль: password)</span>
                </div>
            </footer>
        </div>

        {{-- Фиксированная иконка WhatsApp слева --}}
        <a href="https://api.whatsapp.com/send/?phone=%2B77070000103&text&type=phone_number&app_absent=0"
           target="_blank"
           rel="noopener noreferrer"
           class="fixed left-4 bottom-8 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg hover:bg-[#20BD5A] hover:scale-110 transition-all duration-200"
           title="Написать в WhatsApp"
           aria-label="Написать в WhatsApp">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
    </body>
</html>
