<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        $user->roles()->attach(3); // 3 = applicant role

        return redirect('/login')->with('success', 'Registered successfully. Please log in.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard'); // change this to your actual dashboard route
        }

        return back()->withErrors(['login_error' => 'Invalid credentials.']);
    }

    // Show forgot password form
    public function showForgotForm()
    {
        return view('forgot');
    }

    // Handle forgot password form submission
    public function submitForgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Password reset link sent!'])
            : back()->withErrors(['email' => __($status)]);
    }
}
