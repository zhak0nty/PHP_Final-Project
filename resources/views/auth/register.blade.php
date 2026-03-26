@extends('layouts.app')

@section('title', 'Регистрация — MedBooking')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="max-w-[400px] mx-auto">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Регистрация клиента</h1>
                <p class="mt-2 text-gray-500 text-sm">Роль <span class="text-[#6B21A8] font-medium">client</span>. Админ и врач — через demo-аккаунты.</p>
            </div>
            @if(auth()->check() && ! auth()->user()->isClient())
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-left text-sm text-amber-950">
                    Сейчас вы вошли как <strong>{{ auth()->user()->role }}</strong>. Здесь создаётся аккаунт <strong>клиента</strong> для привязки гостевой записи. После отправки формы вы войдёте под новым пользователем.
                </div>
            @endif
            @if($errors->has('email') && old('email'))
                <div class="mb-4 rounded-xl border border-[#6B21A8]/25 bg-[#6B21A8]/5 px-4 py-3 text-center text-sm text-gray-800">
                    <a href="{{ route('login', ['email' => old('email')]) }}" class="font-medium text-[#6B21A8] hover:underline">Войти с этим email</a>
                    — гостевая запись привяжется после входа.
                </div>
            @endif
            <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ url('/register') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Имя</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="Иван Иванов">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', request('email')) }}" required
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="example@mail.ru">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Пароль</label>
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="Минимум 8 символов">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Подтверждение пароля</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-[#6B21A8] focus:ring-2 focus:ring-[#6B21A8]/20 focus:outline-none transition-colors"
                            placeholder="Повторите пароль">
                    </div>
                    <div class="flex items-center justify-between text-sm pt-1">
                        <span class="text-gray-500">Уже есть аккаунт?</span>
                        <a href="{{ route('login') }}" class="font-medium text-[#6B21A8] hover:text-[#5B1B8A]">Войти</a>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#6B21A8] px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-[#5B1B8A] focus:outline-none focus:ring-2 focus:ring-[#6B21A8] focus:ring-offset-2 transition-colors">
                        Зарегистрироваться
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
