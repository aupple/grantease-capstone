<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        return Notification::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'message' => 'required|string',
            'is_read' => 'boolean'
        ]);
        return Notification::create($validated);
    }

    public function show($id) {
        return Notification::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $note = Notification::findOrFail($id);
        $note->update($request->all());
        return $note;
    }

    public function destroy($id) {
        $note = Notification::findOrFail($id);
        $note->delete();
        return response()->json(['message' => 'Deleted']);
    }
}