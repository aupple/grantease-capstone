<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholar;
use App\Models\ScholarMonitoring;

class ScholarMonitoringController extends Controller
{
    // Show all monitoring records
   public function index()
{
    // Load monitoring records with scholar + user
    $monitorings = ScholarMonitoring::with('scholar.user')->get();

    // Load all scholars (for the top "Monitoring of Scholars" table)
    $scholars = Scholar::with('user')->get();

    return view('admin.reports.monitoring', compact('monitorings', 'scholars'));
}


    // Show create form (optional)
    public function create($scholarId)
    {
        $scholar = Scholar::findOrFail($scholarId);
        return view('admin.monitorings.create', compact('scholar'));
    }

    // Store a new monitoring record
    public function store(Request $request, $scholarId)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'level' => 'required|string',
            'course' => 'nullable|string',
            'school' => 'nullable|string',
            'status_code' => 'nullable|string',
            'status' => 'nullable|string',
            'remarks' => 'nullable|string',
            'year_awarded' => 'nullable|integer',
            'degree_type' => 'nullable|string',
            'total' => 'nullable|integer',
        ]);

        $validated['scholar_id'] = $scholarId; // 🔗 link to scholar

        ScholarMonitoring::create($validated);

        return redirect()->route('scholars.show', $scholarId)
            ->with('success', 'Monitoring record added successfully.');
    }

    // Edit a record
    public function edit($id)
    {
        $monitoring = ScholarMonitoring::findOrFail($id);
        return view('admin.monitorings.edit', compact('monitoring'));
    }

    // Update a record
    public function update(Request $request, $id)
    {
        $monitoring = ScholarMonitoring::findOrFail($id);

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'level' => 'required|string',
            'course' => 'nullable|string',
            'school' => 'nullable|string',
            'status_code' => 'nullable|string',
            'status' => 'nullable|string',
            'remarks' => 'nullable|string',
            'year_awarded' => 'nullable|integer',
            'degree_type' => 'nullable|string',
            'total' => 'nullable|integer',
        ]);

        $monitoring->update($validated);

        return redirect()->back()->with('success', 'Monitoring record updated successfully.');
    }

    // Delete a record
    public function destroy($id)
    {
        $monitoring = ScholarMonitoring::findOrFail($id);
        $scholarId = $monitoring->scholar_id; // save scholar ID before delete
        $monitoring->delete();

        return redirect()->route('scholars.show', $scholarId)
            ->with('success', 'Monitoring record deleted successfully.');
    }
}
