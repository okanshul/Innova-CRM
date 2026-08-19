<?php

use App\Support\Formatter;
use App\Support\SettingsManager;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return SettingsManager::get($key, $default);
    }
}

if (!function_exists('format_date')) {
    function format_date(mixed $value): string
    {
        return Formatter::date($value);
    }
}

if (!function_exists('format_time')) {
    function format_time(mixed $value): string
    {
        return Formatter::time($value);
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime(mixed $value): string
    {
        return Formatter::datetime($value);
    }
}

if (!function_exists('format_currency')) {
    function format_currency(mixed $value, ?string $currencyCode = null): string
    {
        return Formatter::currency($value, $currencyCode);
    }
}

if (!function_exists('format_number')) {
    function format_number(mixed $value, int $decimals = 2): string
    {
        return Formatter::number($value, $decimals);
    }
}
