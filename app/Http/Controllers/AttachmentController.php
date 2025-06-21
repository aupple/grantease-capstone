<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    // GET /api/attachments
    public function index()
    {
        return response()->json(Attachment::with('application')->get(), 200);
    }

    // GET /api/attachments/{id}
    public function show($id)
    {
        $attachment = Attachment::with('application')->find($id);

        if (!$attachment) {
            return response()->json(['message' => 'Attachment not found'], 404);
        }

        return response()->json($attachment, 200);
    }

    // POST /api/attachments
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:application_forms,application_id',
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string',
            'file_type' => 'required|string|max:100',
            'uploaded_at' => 'nullable|date',
        ]);

        $attachment = Attachment::create($validated);

        return response()->json($attachment, 201);
    }

    // PUT /api/attachments/{id}
    public function update(Request $request, $id)
    {
        $attachment = Attachment::findOrFail($id);

        $validated = $request->validate([
            'file_name' => 'sometimes|string|max:255',
            'file_path' => 'sometimes|string',
            'file_type' => 'sometimes|string|max:100',
            'uploaded_at' => 'nullable|date',
        ]);

        $attachment->update($validated);

        return response()->json($attachment, 200);
    }

    // DELETE /api/attachments/{id}
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted'], 200);
    }
}
