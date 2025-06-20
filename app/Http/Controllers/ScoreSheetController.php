<?php

namespace App\Http\Controllers;

use App\Models\ScoreSheet;
use Illuminate\Http\Request;

class ScoreSheetController extends Controller
{
    public function index() {
        return ScoreSheet::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'evaluation_id' => 'required|exists:evaluations,evaluation_id',
            'criteria_name' => 'required|string',
            'score' => 'required|numeric',
            'date_of_interview' => 'required|date',
            'status' => 'required|string'
        ]);
        return ScoreSheet::create($validated);
    }

    public function show($id) {
        return ScoreSheet::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $sheet = ScoreSheet::findOrFail($id);
        $sheet->update($request->all());
        return $sheet;
    }

    public function destroy($id) {
        $sheet = ScoreSheet::findOrFail($id);
        $sheet->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
