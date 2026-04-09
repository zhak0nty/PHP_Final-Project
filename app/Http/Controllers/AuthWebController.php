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
        // Same as register: only clients go to the dashboard; admin/doctor sees the login form.
        if (Auth::check() && Auth::user()->isClient()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.login');
    }

    public function showRegisterForm()
    {
        // Already a client — dashboard. Admin/doctor stays on client registration
        // (guest booking → Register); otherwise redirect would send them to admin.
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
                ->withErrors(['email' => 'Invalid email or password.'])
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
            'email.unique' => 'This email is already registered. Sign in with it — your guest booking will be linked to your account.',
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
