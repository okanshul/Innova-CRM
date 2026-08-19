<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CrmSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('settings.view');

        $settings = CrmSetting::getAllSettings();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('settings.edit');

        $validator = Validator::make($request->all(), [
            'system_logo_file' => 'nullable|image|max:2048',
            'favicon_file' => 'nullable|image|max:2048',
            'system_email' => 'nullable|email',
            'company_profile_email' => 'nullable|email',
            'company_profile_website' => 'nullable|url',
            'app_url' => 'nullable|url',
            'primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'deal_close_days' => 'nullable|integer|min:1',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error. Please check your inputs.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                // File Upload 1: System Logo
                if ($request->hasFile('system_logo_file') && $request->file('system_logo_file')->isValid()) {
                    $oldLogo = CrmSetting::get('system_logo');
                    if ($oldLogo && file_exists(public_path($oldLogo))) {
                        @unlink(public_path($oldLogo));
                    }

                    $file = $request->file('system_logo_file');
                    $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                    $uploadPath = public_path('uploads/settings');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $file->move($uploadPath, $filename);
                    CrmSetting::set('system_logo', 'uploads/settings/' . $filename);
                }

                // File Upload 2: Favicon
                if ($request->hasFile('favicon_file') && $request->file('favicon_file')->isValid()) {
                    $oldFavicon = CrmSetting::get('favicon');
                    if ($oldFavicon && file_exists(public_path($oldFavicon))) {
                        @unlink(public_path($oldFavicon));
                    }

                    $file = $request->file('favicon_file');
                    $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                    $uploadPath = public_path('uploads/settings');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $file->move($uploadPath, $filename);
                    CrmSetting::set('favicon', 'uploads/settings/' . $filename);
                }

                // Process regular settings fields
                $settings = $request->except(['_token', '_method', 'system_logo_file', 'favicon_file']);

                foreach ($settings as $key => $value) {
                    if (is_scalar($value) || is_array($value)) {
                        CrmSetting::set($key, $value);
                    }
                }

                AuditLog::record('Updated', 'Settings');
            });

            event(new \App\Events\SettingsChanged($request->all()));

            return response()->json([
                'success' => true,
                'message' => 'System settings saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testEmail(Request $request)
    {
        Gate::authorize('settings.edit');

        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|numeric',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide valid SMTP credentials before sending a test email.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Dynamic SMTP configuration
            config([
                'mail.mailers.dynamic_smtp' => [
                    'transport' => 'smtp',
                    'host' => $request->input('smtp_host'),
                    'port' => $request->input('smtp_port'),
                    'encryption' => strtolower($request->input('smtp_encryption', 'tls')) === 'none' ? null : strtolower($request->input('smtp_encryption', 'tls')),
                    'username' => $request->input('smtp_username'),
                    'password' => $request->input('smtp_password'),
                    'timeout' => 5,
                ],
                'mail.from' => [
                    'address' => $request->input('smtp_username'),
                    'name' => CrmSetting::get('company_name', 'InnovaCRM'),
                ]
            ]);

            $recipient = $request->user()->email ?? $request->input('smtp_username');

            // Send dynamic test email
            Mail::mailer('dynamic_smtp')->raw('This is a test email from InnovaCRM to verify your SMTP configuration.', function ($message) use ($recipient) {
                $message->to($recipient)->subject('InnovaCRM - Test Email Configuration');
            });

            AuditLog::record('Sent Test Email', 'Email Settings');

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $recipient
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 422);
        }
    }
}
