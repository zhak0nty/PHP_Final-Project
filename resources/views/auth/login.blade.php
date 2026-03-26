@extends('layouts.app')

@section('title', 'Вход — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="max-w-[400px] mx-auto">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Вход</h1>
                <p class="mt-2 text-gray-500 text-sm">Demo: client@example.com, admin@example.com, doctor1@example.com · пароль <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">password</code></p>
            </div>
            @if(auth()->check() && ! auth()->user()->isClient())
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-left text-sm text-amber-950">
                    Вы вошли как <strong>{{ auth()->user()->role }}</strong>. Введите данные <strong>клиента</strong>, чтобы переключиться на этот аккаунт, или сначала
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-medium text-[#6B21A8] underline bg-transparent border-0 p-0 cursor-pointer hover:text-[#5B1B8A]">выйдите</button>
                    </form>.
                </div>
            @endif
            <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', request('email')) }}" required autofocus
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="example@mail.ru">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Пароль</label>
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="••••••••">
                    </div>
                    <div class="flex items-center justify-between text-sm pt-1">
                        <label class="inline-flex items-center gap-2 text-gray-500 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#6B21A8] focus:ring-[#6B21A8]">
                            Запомнить меня
                        </label>
                        <a href="{{ route('register') }}" class="font-medium text-[#6B21A8] hover:text-[#5B1B8A]">Нет аккаунта? Регистрация</a>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] focus:outline-none focus:ring-2 focus:ring-[#6B21A8] focus:ring-offset-2 transition-colors">
                        Войти
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
