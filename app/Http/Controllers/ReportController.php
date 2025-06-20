<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        return Report::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'report_name' => 'required|string',
            'report_content' => 'required|string'
        ]);
        return Report::create($validated);
    }

    public function show($id) {
        return Report::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $report = Report::findOrFail($id);
        $report->update($request->all());
        return $report;
    }

    public function destroy($id) {
        $report = Report::findOrFail($id);
        $report->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
