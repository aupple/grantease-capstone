<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index() {
        return Evaluation::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'application_id' => 'required|exists:application_forms,application_id',
            'user_id' => 'required|exists:users,user_id',
            'evaluation_date' => 'required|date',
        ]);
        return Evaluation::create($validated);
    }

    public function show($id) {
        return Evaluation::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $record = Evaluation::findOrFail($id);
        $record->update($request->all());
        return $record;
    }

    public function destroy($id) {
        $record = Evaluation::findOrFail($id);
        $record->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

