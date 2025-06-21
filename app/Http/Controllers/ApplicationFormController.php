<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use Illuminate\Http\Request;

class ApplicationFormController extends Controller
{
    // GET /api/application-forms
    public function index()
    {
        return response()->json(ApplicationForm::with(['user', 'attachments'])->get(), 200);
    }

    // POST /api/application-forms
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',  // use 'id' if your users table uses default primary key
            'scholarship_type' => 'required|string',
            'status' => 'required|string|in:pending,approved,rejected',
            'date_submitted' => 'required|date',
        ]);

        $form = ApplicationForm::create($validated);

        return response()->json($form, 201);
    }

    // GET /api/application-forms/{id}
    public function show($id)
    {
        $form = ApplicationForm::with(['user', 'attachments'])->find($id);

        if (!$form) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        return response()->json($form, 200);
    }

    // PUT /api/application-forms/{id}
    public function update(Request $request, $id)
    {
        $form = ApplicationForm::findOrFail($id);

        $validated = $request->validate([
            'scholarship_type' => 'sometimes|string',
            'status' => 'sometimes|string|in:pending,approved,rejected',
            'date_submitted' => 'sometimes|date',
        ]);

        $form->update($validated);

        return response()->json($form, 200);
    }

    // DELETE /api/application-forms/{id}
    public function destroy($id)
    {
        $form = ApplicationForm::findOrFail($id);
        $form->delete();

        return response()->json(['message' => 'Application deleted'], 200);
    }
}
