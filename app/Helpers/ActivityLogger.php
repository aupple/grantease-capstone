<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $details = null)
    {
        try {
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'action' => $action,
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}