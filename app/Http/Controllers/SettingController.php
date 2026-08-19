<?php

namespace App\Http\Controllers;

use App\Models\CrmSetting;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('settings.view');

        $settings = CrmSetting::getAllSettings();

        return view('settings.index', compact('settings'));
    }
}
