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
use Illuminate\Validation\Rules\Password;
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
            'password' => [
                'required',
                'confirmed',
                Password::min(8)       // at least 8 characters
                    ->mixedCase()      // at least 1 uppercase + 1 lowercase
                    ->letters()        // must contain letters
                    ->numbers()        // must contain numbers
                    ->symbols(),       // must contain symbols
            ],
            'program_type' => ['required', 'in:DOST,CHED'], // Add validation for program_type
        ]);

        // Create a new user with default applicant role
        $user = User::create([
            'first_name' => Str::ucfirst(strtolower($request->first_name)),
            'middle_name' => $request->middle_name ? Str::ucfirst(strtolower($request->middle_name)) : null,
            'last_name' => Str::ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'program_type' => $request->program_type, // Store the selected program_type
            'role_id' => 2, // 1 = Admin, 2 = Applicant (adjust if CHED needs a different role)
            //'email_verified_at' => now(), // Comment out if you want actual email verification
        ]);

        event(new Registered($user));

        Auth::login($user);

        // If using email verification, send the notification
        $user->sendEmailVerificationNotification();

        // Redirect to verification notice if verification is required
        return redirect()->route('verification.notice');

        // If skipping verification, uncomment and use this instead:
        // return redirect()->route('dashboard'); // Where you'll handle program_type-based redirection
    }
}
