<?php

namespace App\Http\Controllers;

use App\Models\ApplicationForm;
use Illuminate\Http\Request;

class ApplicationFormController extends Controller
{
    public function index() {
        return response()->json(ApplicationForm::all());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'scholarship_type' => 'required|string',
            'status' => 'required|string',
            'date_submitted' => 'required|date',
        ]);
        return ApplicationForm::create($validated);
    }

    public function show($id) {
        return ApplicationForm::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $form = ApplicationForm::findOrFail($id);
        $form->update($request->all());
        return $form;
    }

    public function destroy($id) {
        $form = ApplicationForm::findOrFail($id);
        $form->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
