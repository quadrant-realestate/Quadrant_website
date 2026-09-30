<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    // ============================================================
    // INDEX — Show settings grouped by tab
    // ============================================================
    public function index(Request $request)
    {
        $group = $request->group ?? 'general';

        // Get all settings grouped
        $allSettings = DB::table('settings')->orderBy('id')->get();

        // Group them
        $grouped = $allSettings->groupBy('group');

        // Current group settings as key => value for easy access in blade
        $settings = DB::table('settings')
                       ->where('group', $group)
                       ->orderBy('id')
                       ->get();

        return view('admin.settings.index', compact('settings', 'grouped', 'group'));
    }

    // ============================================================
    // UPDATE — Save settings
    // ============================================================
    public function update(Request $request)
    {
        $group = $request->group ?? 'general';

        if ($request->settings && is_array($request->settings)) {
            foreach ($request->settings as $key => $value) {

                // Check if this setting is image type
                $setting = DB::table('settings')->where('key', $key)->first();

                if ($setting && $setting->type === 'image') {

                    // Handle file upload for image type
                    if ($request->hasFile("settings.$key")) {
                        $file = $request->file("settings.$key");
                        $name = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads/settings'), $name);
                        $value = 'uploads/settings/' . $name;
                    } else {
                        // Keep existing value
                        continue;
                    }
                }

                DB::table('settings')
                ->where('key', $key)
                ->update([
                    'value'      => $value,
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.settings.index', ['group' => $group])
                        ->with('success', 'Settings saved successfully.');
    }
}