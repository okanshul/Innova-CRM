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

        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            CrmSetting::set($key, $value);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully.'
        ]);
    }
}
