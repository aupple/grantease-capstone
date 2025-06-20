<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        return Role::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'role_name' => 'required|string|unique:roles'
        ]);
        return Role::create($validated);
    }

    public function show($id) {
        return Role::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return $role;
    }

    public function destroy($id) {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
