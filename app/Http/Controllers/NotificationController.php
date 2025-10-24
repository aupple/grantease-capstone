<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function redirect($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();

        $data = $notification->data;

        $notification->markAsRead();

        if (!empty($data['route_name'])) {
            $url = route($data['route_name'], $data['route_params'] ?? []);

            if (!empty($data['focus'])) {
                $separator = (parse_url($url, PHP_URL_QUERY) ? '&' : '?');
                $url .= $separator . 'focus=' . urlencode($data['focus']);
            }

            return redirect($url);
        }

        return redirect()->route('applicant.dashboard');
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'ok']);
    }
}

