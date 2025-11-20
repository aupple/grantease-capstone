<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ActivityLogger;

class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * @param array $roles Expected format: ['1'] for admin, ['2'] for any applicant, ['2:DOST'] for DOST only, ['2:CHED'] for CHED only
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check each allowed role
        foreach ($roles as $role) {
            // Check if role includes program type (e.g., "2:DOST" or "2:CHED")
            if (str_contains($role, ':')) {
                [$roleId, $programType] = explode(':', $role);
                
                if ($user->role_id == $roleId && $user->program_type === $programType) {
                    return $next($request);
                }
            } else {
                // Simple role check (e.g., "1" for admin, "2" for any applicant)
                if ($user->role_id == $role) {
                    return $next($request);
                }
            }
        }

        // Log unauthorized access attempt
        ActivityLogger::log('UNAUTHORIZED_ACCESS_ATTEMPT', 
            'Attempted URL: ' . $request->fullUrl() . 
            ' | User Role: ' . $user->role_id . 
            ' | Program Type: ' . ($user->program_type ?? 'N/A') .
            ' | IP: ' . $request->ip()
        );

        abort(403, 'Unauthorized access - You do not have permission to access this page.');
    }
}