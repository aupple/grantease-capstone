<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('profile.admin-edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Add other fields as needed
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
