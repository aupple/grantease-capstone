<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    // GET /api/scholarships
    public function index()
    {
        return response()->json(Scholarship::all(), 200);
    }

    // GET /api/scholarships/{id}
    public function show($id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        return response()->json($scholarship, 200);
    }

    // POST /api/scholarships
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        $scholarship = Scholarship::create($validated);

        return response()->json($scholarship, 201);
    }

    // PUT /api/scholarships/{id}
    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'sometimes|date',
        ]);

        $scholarship->update($validated);

        return response()->json($scholarship, 200);
    }

    // DELETE /api/scholarships/{id}
    public function destroy($id)
    {
        $scholarship = Scholarship::find($id);

        if (!$scholarship) {
            return response()->json(['message' => 'Scholarship not found'], 404);
        }

        $scholarship->delete();

        return response()->json(['message' => 'Scholarship deleted successfully'], 200);
    }
}
