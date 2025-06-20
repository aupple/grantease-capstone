<?php

namespace App\Http\Controllers;

use App\Models\Remark;
use Illuminate\Http\Request;

class RemarkController extends Controller
{
    public function index() {
        return Remark::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'evaluation_id' => 'required|exists:evaluations,evaluation_id',
            'application_id' => 'required|exists:application_forms,application_id',
            'user_id' => 'required|exists:users,user_id',
            'remark_text' => 'required|string',
            'remark_note' => 'nullable|string'
        ]);
        return Remark::create($validated);
    }

    public function show($id) {
        return Remark::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $remark = Remark::findOrFail($id);
        $remark->update($request->all());
        return $remark;
    }

    public function destroy($id) {
        $remark = Remark::findOrFail($id);
        $remark->delete();
        return response()->json(['message' => 'Deleted']);
    }
}