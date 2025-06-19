<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles',
        ]);

        Role::create(['role_name' => $request->role_name]);

        return response()->json(['message' => 'Role added']);
    }
}

