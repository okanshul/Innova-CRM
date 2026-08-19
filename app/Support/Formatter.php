<?php

namespace App\Support;

use Carbon\Carbon;

class Formatter
{
    public static function date(mixed $value): string
    {
        if (!$value) {
            return '';
        }

        try {
            $tz = setting('timezone', setting('localization_timezone', 'Asia/Kolkata'));
            $date = $value instanceof Carbon ? $value->setTimezone($tz) : Carbon::parse($value)->setTimezone($tz);
            $format = setting('date_format', setting('localization_date_format', 'MMM D, YYYY'));

            $phpFormat = match ($format) {
                'YYYY-MM-DD' => 'Y-m-d',
                'DD/MM/YYYY' => 'd/m/Y',
                default => 'M j, Y',
            };

            return $date->format($phpFormat);
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    public static function time(mixed $value): string
    {
        if (!$value) {
            return '';
        }

        try {
            $tz = setting('timezone', setting('localization_timezone', 'Asia/Kolkata'));
            $date = $value instanceof Carbon ? $value->setTimezone($tz) : Carbon::parse($value)->setTimezone($tz);
            $tf = setting('time_format', setting('localization_time_format', '12'));

            return $tf === '24' ? $date->format('H:i') : $date->format('g:i A');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    public static function datetime(mixed $value): string
    {
        if (!$value) {
            return '';
        }

        return static::date($value) . ', ' . static::time($value);
    }

    public static function number(mixed $value, int $decimals = 2): string
    {
        if ($value === null || $value === '') {
            return '0.00';
        }

        $num = (float) $value;
        $nf = setting('localization_number_format', '1,234.56');

        return match ($nf) {
            '1.234,56' => number_format($num, $decimals, ',', '.'),
            '1 234,56' => number_format($num, $decimals, ',', ' '),
            default => number_format($num, $decimals, '.', ','),
        };
    }

    public static function currency(mixed $value, ?string $currencyCode = null): string
    {
        $code = $currencyCode ?? setting('currency_symbol', setting('localization_currency', 'USD'));
        $symbol = match ($code) {
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹',
            'USD', '$' => '$',
            default => '$',
        };

        return $symbol . static::number($value, 2);
    }
}
