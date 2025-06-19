<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registerUser(Request $request)
{
    $request->validate([
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
        'first_name' => 'required',
        'last_name' => 'required',
        'role' => 'required|in:admin,applicant',
    ]);

    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
    ]);

    $roleId = $request->role === 'admin' ? 2 : 3;
    $user->roles()->attach($roleId);

    return response()->json(['message' => ucfirst($request->role) . ' created successfully']);
}
}
