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
            'sidebar_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
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
                if ($request->input('remove_system_logo') == '1') {
                    $oldLogo = CrmSetting::get('system_logo');
                    if ($oldLogo && file_exists(public_path($oldLogo))) {
                        @unlink(public_path($oldLogo));
                    }
                    CrmSetting::set('system_logo', '');
                } else if ($request->hasFile('system_logo_file') && $request->file('system_logo_file')->isValid()) {
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
                if ($request->input('remove_favicon') == '1') {
                    $oldFavicon = CrmSetting::get('favicon');
                    if ($oldFavicon && file_exists(public_path($oldFavicon))) {
                        @unlink(public_path($oldFavicon));
                    }
                    CrmSetting::set('favicon', '');
                } else if ($request->hasFile('favicon_file') && $request->file('favicon_file')->isValid()) {
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
                $settings = $request->except(['_token', '_method', 'system_logo_file', 'favicon_file', 'remove_system_logo', 'remove_favicon']);

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
            $smtpUsername = $request->input('smtp_username');
            $userEmail = $request->user()->email ?? null;

            // Determine valid From address (RFC 2822 compliant)
            $fromAddress = filter_var($smtpUsername, FILTER_VALIDATE_EMAIL)
                ? $smtpUsername
                : (filter_var($userEmail, FILTER_VALIDATE_EMAIL) ? $userEmail : 'no-reply@innovacrm.com');

            // Determine valid recipient address (RFC 2822 compliant)
            $recipient = filter_var($userEmail, FILTER_VALIDATE_EMAIL)
                ? $userEmail
                : (filter_var($smtpUsername, FILTER_VALIDATE_EMAIL) ? $smtpUsername : 'admin@innovacrm.com');

            // Dynamic SMTP configuration
            config([
                'mail.mailers.dynamic_smtp' => [
                    'transport' => 'smtp',
                    'host' => $request->input('smtp_host'),
                    'port' => $request->input('smtp_port'),
                    'encryption' => strtolower($request->input('smtp_encryption', 'tls')) === 'none' ? null : strtolower($request->input('smtp_encryption', 'tls')),
                    'username' => $smtpUsername,
                    'password' => $request->input('smtp_password'),
                    'timeout' => 5,
                ],
                'mail.from' => [
                    'address' => $fromAddress,
                    'name' => CrmSetting::get('company_name', 'InnovaCRM'),
                ]
            ]);

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

    public function reset(Request $request)
    {
        Gate::authorize('settings.edit');

        try {
            DB::transaction(function () {
                $defaults = [
                    'company_name' => 'InnovaCRM Inc.',
                    'system_email' => 'admin@innovacrm.com',
                    'currency_symbol' => 'USD',
                    'date_format' => 'MMM D, YYYY',
                    'timezone' => 'Asia/Kolkata',
                    'time_format' => '12',
                    'items_per_page' => '10',
                    'language' => 'en',
                    'system_logo' => '',
                    'favicon' => '',
                    'maintenance_mode' => '0',
                    'enable_recaptcha' => '1',
                    'default_landing_page' => 'dashboard',
                    'deal_close_days' => '30',
                    'auto_logout' => '1 hour',
                    'email_verification' => '1',
                    'primary_color' => '#5030FF',
                    'secondary_color' => '#000000',
                    'sidebar_bg_color' => '#0B0F19',
                    'app_name' => 'InnovaCRM',
                    'app_url' => 'https://crm.innovacrm.com',
                    'company_profile_name' => 'InnovaCRM Inc.',
                    'company_profile_email' => 'info@innovacrm.com',
                    'company_profile_phone' => '+1 (800) 123-4567',
                    'company_profile_website' => 'https://www.innovacrm.com',
                    'company_address_1' => '123 Business Street',
                    'company_address_2' => 'Suite 100',
                    'company_city' => 'San Francisco',
                    'company_state' => 'California',
                    'company_postal' => '94107',
                    'company_country' => 'United States',
                    'localization_language' => 'en',
                    'localization_date_format' => 'MMM D, YYYY',
                    'localization_time_format' => '12',
                    'localization_timezone' => 'Asia/Kolkata',
                    'localization_first_day' => 'Monday',
                    'localization_number_format' => '1,234.56',
                    'localization_currency' => 'USD',
                    'localization_measurement' => 'Metric',
                    'smtp_driver' => 'SMTP',
                    'smtp_port' => '587',
                    'smtp_host' => 'smtp.innovacrm.com',
                    'smtp_encryption' => 'TLS',
                    'smtp_username' => 'no-reply@innovacrm.com',
                    'smtp_password' => 'secret_smtp_pass',
                    'pref_sys_email' => '1',
                    'pref_notifications' => '1',
                    'pref_verification' => '1',
                    'pref_change_email' => '0',
                    'channel_inapp' => '1',
                    'channel_email' => '1',
                    'channel_sms' => '0',
                    'channel_browser' => '1',
                    'notify_new_lead' => '1',
                    'notify_deal_stage' => '1',
                    'notify_task_due' => '1',
                    'notify_new_deal' => '1',
                    'notify_new_user' => '1',
                    'notify_meeting' => '1',
                    'notify_invoice' => '0',
                    'notify_payment' => '1',
                    'sec_min_password' => '8',
                    'sec_req_number' => '1',
                    'sec_req_special' => '1',
                    'sec_password_expiry' => '90',
                    'sec_req_2fa' => '0',
                    'sec_allow_2fa' => '1',
                    'sec_session_timeout' => '1h',
                    'sec_remember_duration' => '7d',
                    'crm_default_lead_status' => 'New',
                    'crm_default_deal_stage' => 'Prospecting',
                    'crm_default_source' => 'Website',
                    'crm_lead_conversion' => 'Create Contact & Deal',
                    'crm_enable_scoring' => '1',
                    'crm_enable_forecast' => '1',
                    'crm_enable_reminders' => '1',
                    'crm_auto_assign' => '0',
                ];

                foreach ($defaults as $key => $value) {
                    CrmSetting::set($key, $value);
                }

                AuditLog::record('Reset', 'Settings');
            });

            event(new \App\Events\SettingsChanged([]));

            return response()->json([
                'success' => true,
                'message' => 'Settings have been reset to default values successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
