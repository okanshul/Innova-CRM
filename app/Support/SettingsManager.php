<?php

namespace App\Support;

use App\Models\CrmSetting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SettingsManager
{
    protected static ?array $cachedSettings = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$cachedSettings === null) {
            try {
                if (Schema::hasTable('crm_settings')) {
                    static::$cachedSettings = CrmSetting::getAllSettings();
                } else {
                    static::$cachedSettings = [];
                }
            } catch (\Throwable $e) {
                static::$cachedSettings = [];
            }
        }

        return static::$cachedSettings[$key] ?? $default;
    }

    public static function flushCache(): void
    {
        static::$cachedSettings = null;
    }

    public static function applyRuntimeConfig(): void
    {
        try {
            if (!Schema::hasTable('crm_settings')) {
                return;
            }

            // App Name & URL
            $appName = static::get('app_name', static::get('company_name'));
            if ($appName) {
                Config::set('app.name', $appName);
            }

            $appUrl = static::get('app_url');
            if ($appUrl) {
                Config::set('app.url', $appUrl);
            }

            // Timezone & Locale
            $tz = static::get('timezone', static::get('localization_timezone'));
            if ($tz) {
                @date_default_timezone_set($tz);
                Config::set('app.timezone', $tz);
            }

            $lang = static::get('language', static::get('localization_language'));
            if ($lang) {
                App::setLocale($lang);
            }

            // SMTP Mailer Override
            $host = static::get('smtp_host');
            if ($host) {
                Config::set('mail.mailers.smtp.host', $host);
                Config::set('mail.mailers.smtp.port', static::get('smtp_port', 587));
                $enc = strtolower(static::get('smtp_encryption', 'tls'));
                Config::set('mail.mailers.smtp.encryption', $enc === 'none' ? null : $enc);
                Config::set('mail.mailers.smtp.username', static::get('smtp_username'));
                Config::set('mail.mailers.smtp.password', static::get('smtp_password'));

                if (static::get('smtp_username')) {
                    Config::set('mail.from.address', static::get('smtp_username'));
                    Config::set('mail.from.name', $appName ?? 'InnovaCRM');
                }
            }

            // Session Timeout (parse 1h, 30m, 2h)
            $timeoutStr = static::get('sec_session_timeout', '1h');
            $minutes = 60;
            if (str_contains($timeoutStr, 'm')) {
                $minutes = (int) str_replace('m', '', $timeoutStr);
            } elseif (str_contains($timeoutStr, 'h')) {
                $minutes = ((int) str_replace('h', '', $timeoutStr)) * 60;
            }
            Config::set('session.lifetime', max(1, $minutes));

        } catch (\Throwable $e) {
            // Ignore during setup/migrations
        }
    }
}
