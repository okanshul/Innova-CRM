<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('settings.view');

        $settings = CrmSetting::all();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('settings.edit');

        // Handle File Uploads (System Logo & Favicon)
        if ($request->hasFile('system_logo_file') && $request->file('system_logo_file')->isValid()) {
            $file = $request->file('system_logo_file');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/settings');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            CrmSetting::set('system_logo', 'uploads/settings/' . $filename);
        }

        if ($request->hasFile('favicon_file') && $request->file('favicon_file')->isValid()) {
            $file = $request->file('favicon_file');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/settings');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            CrmSetting::set('favicon', 'uploads/settings/' . $filename);
        }

        // Process all regular settings inputs
        $settings = $request->except(['_token', '_method', 'system_logo_file', 'favicon_file']);

        foreach ($settings as $key => $value) {
            if (is_scalar($value) || is_array($value)) {
                CrmSetting::set($key, $value);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.'
        ]);
    }
}
