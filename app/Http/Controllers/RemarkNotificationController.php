<?php

namespace App\Http\Controllers;

use App\Models\RemarkNotification;
use Illuminate\Http\Request;

class RemarkNotificationController extends Controller
{
    public function index()
    {
        return RemarkNotification::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'remark_id' => 'required|exists:remarks,remark_id',
            'notification_id' => 'required|exists:notifications,notification_id',
        ]);

        return RemarkNotification::create($validated);
    }

    public function destroy($id)
    {
        $item = RemarkNotification::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
