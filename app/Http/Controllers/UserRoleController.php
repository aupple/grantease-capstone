<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        return UserRole::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        return UserRole::create($validated);
    }

    public function destroy($id)
    {
        $userRole = UserRole::findOrFail($id);
        $userRole->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
