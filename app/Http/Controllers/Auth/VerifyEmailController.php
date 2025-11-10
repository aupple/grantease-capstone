<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        // Redirect based on role and program_type
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard')->with('verified', 1);
        } elseif ($user->role_id == 2) {
            if ($user->program_type === 'CHED') {
                return redirect()->route('ched.dashboard')->with('verified', 1);
            } else {
                return redirect()->route('applicant.dashboard')->with('verified', 1);
            }
        }

        abort(403);
    }
}
