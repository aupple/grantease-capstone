<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Helpers\ActivityLogger; // ✅ Add this

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Role-based redirection
        if ($user->role_id == 1) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->role_id == 2) {
            if ($user->program_type === 'CHED') {
                return redirect()->intended('/ched/dashboard');
            }
            // Default: DOST applicant
            return redirect()->intended('/applicant/dashboard');
        }

        // Default fallback
        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ✅ Log logout BEFORE logging out (so we still have user info)
        $user = Auth::user();
        if ($user) {
            $roleName = $user->role->role_name ?? 'Unknown';
            $programType = $user->program_type ?? 'N/A';
            
            ActivityLogger::log(
                'LOGOUT',
                "User logged out | Role: {$roleName} | Program: {$programType}"
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}