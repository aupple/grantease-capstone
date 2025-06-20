<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    public function index() {
        return Appeal::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'application_id' => 'required|exists:application_forms,application_id',
            'user_id' => 'required|exists:users,user_id',
            'appeal_reason' => 'required|string',
            'status' => 'required|string'
        ]);
        return Appeal::create($validated);
    }

    public function show($id) {
        return Appeal::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $appeal = Appeal::findOrFail($id);
        $appeal->update($request->all());
        return $appeal;
    }

    public function destroy($id) {
        $appeal = Appeal::findOrFail($id);
        $appeal->delete();
        return response()->json(['message' => 'Deleted']);
    }
}