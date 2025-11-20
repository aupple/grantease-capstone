<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'middle_name' => ['nullable', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
        ],
        'program_type' => ['required', 'in:DOST,CHED'],
    ]);

    $user = User::create([
        'first_name' => Str::ucfirst(strtolower($request->first_name)),
        'middle_name' => $request->middle_name ? Str::ucfirst(strtolower($request->middle_name)) : null,
        'last_name' => Str::ucfirst(strtolower($request->last_name)),
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'program_type' => $request->program_type,
        'role_id' => 2,
    ]);

    event(new Registered($user));

    // ✅ Login user temporarily (needed to access verification.notice route)
    Auth::login($user);

    // ✅ Send verification email
    $user->sendEmailVerificationNotification();

    // ✅ Redirect to verification notice page
    return redirect()->route('verification.notice');
}
}