<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthLandingController extends Controller
{
    /**
     * Display the combined login and registration page.
     */
    public function show(Request $request): View
    {
        return view('auth.landing');
    }

    /**
     * Attempt to authenticate the user.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'], 'login')
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor(Auth::user()));
    }

    /**
     * Register a new user and log them in immediately.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('register', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor($user))
            ->with('status', 'Registrasi berhasil. Selamat datang!');
    }

    protected function redirectPathFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'warehouse_manager' => route('admin.warehouse.products.index'),
            'customer' => route('customer.home'),
            default => route('customer.home'),
        };
    }
}
