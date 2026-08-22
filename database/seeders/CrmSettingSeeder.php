<?php

namespace Database\Seeders;

use App\Models\CrmSetting;
use Illuminate\Database\Seeder;

class CrmSettingSeeder extends Seeder
{
    public function run(): void
    {
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
            'secondary_color' => '#F2F4F8',
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
    }
}
