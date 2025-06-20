<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function index() {
        return Attachment::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'application_id' => 'required|exists:application_forms,application_id',
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'file_type' => 'required|string',
        ]);
        return Attachment::create($validated);
    }

    public function show($id) {
        return Attachment::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $item = Attachment::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id) {
        $item = Attachment::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
