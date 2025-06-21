<?php

namespace App\Http\Controllers;

use App\Models\RemarkAttachment;
use Illuminate\Http\Request;

class RemarkAttachmentController extends Controller
{
    public function index()
    {
        return RemarkAttachment::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'remark_id' => 'required|exists:remarks,remark_id',
            'attachment_id' => 'required|exists:attachments,attachment_id',
        ]);

        return RemarkAttachment::create($validated);
    }

    public function destroy($id)
    {
        $item = RemarkAttachment::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
