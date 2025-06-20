<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index() {
        return SystemSetting::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'updated_by_user_id' => 'required|exists:users,user_id',
            'settings_name' => 'required|string',
            'setting_value' => 'required|string'
        ]);
        return SystemSetting::create($validated);
    }

    public function show($id) {
        return SystemSetting::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $setting = SystemSetting::findOrFail($id);
        $setting->update($request->all());
        return $setting;
    }

    public function destroy($id) {
        $setting = SystemSetting::findOrFail($id);
        $setting->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
