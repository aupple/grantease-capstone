<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

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
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
]);


        // Create a new user with default applicant role
        $user = User::create([
            'first_name' => Str::ucfirst(strtolower($request->first_name)),
            'middle_name' => $request->middle_name ? Str::ucfirst(strtolower($request->middle_name)) : null,
            'last_name' => Str::ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // 1 = Admin, 2 = Applicant
            'email_verified_at' => now(), // Mark as verified (optional if not using email verification)
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect applicants to their dashboard
        return redirect()->route('applicant.dashboard');
    }
}
