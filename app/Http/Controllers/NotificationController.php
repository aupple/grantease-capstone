<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Optional list page
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    // Mark the specific notification as read and redirect to intended route + focus
    public function redirect($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();

        $data = $notification->data;

        // Mark read
        $notification->markAsRead();

        // Build URL from stored route_name + route_params (safe)
        if (!empty($data['route_name'])) {
            $url = route($data['route_name'], $data['route_params'] ?? []);

            // append focus as query param
            if (!empty($data['focus'])) {
                $separator = (parse_url($url, PHP_URL_QUERY) ? '&' : '?');
                $url .= $separator . 'focus=' . urlencode($data['focus']);
            }

            return redirect($url);
        }

        // fallback
        return redirect()->route('applicant.dashboard');
    }

    // optional: mark all unread notifications as read (AJAX)
    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'ok']);
    }
}
