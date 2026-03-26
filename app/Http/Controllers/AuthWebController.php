<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService
    ) {}

    public function showLoginForm()
    {
        // Как и регистрация: только клиентов отправляем в кабинет; админ/врач видит форму входа.
        if (Auth::check() && Auth::user()->isClient()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.login');
    }

    public function showRegisterForm()
    {
        // Уже клиент — в кабинет. Админ/врач остаётся на форме регистрации клиента
        // (гостевая запись → «Зарегистрироваться»), иначе редирект вёл в админ-панель.
        if (Auth::check() && Auth::user()->isClient()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Неверный email или пароль.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && $user->isClient()) {
            $this->appointmentService->attachGuestAppointmentsToUser($user);
        }

        return redirect()->intended('/dashboard');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Этот email уже зарегистрирован. Войдите под этим адресом — гостевая запись привяжется к аккаунту.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_CLIENT,
        ]);

        $this->appointmentService->attachGuestAppointmentsToUser($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
