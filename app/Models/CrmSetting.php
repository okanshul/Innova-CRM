<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class CrmSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public static function getAllSettings(): array
    {
        return Cache::remember('crm_settings', 86400, function () {
            $settings = static::all();
            $map = [];
            foreach ($settings as $setting) {
                $val = match ($setting->type) {
                    'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                    'integer', 'int' => (int) $setting->value,
                    'json', 'array' => json_decode($setting->value, true),
                    default => $setting->value,
                };
                $map[$setting->key] = $val;
            }
            return $map;
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAllSettings();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean', 'bool' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $setting->value,
            'json', 'array' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set(string $key, mixed $value, string $type = 'string'): static
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
            $type = 'boolean';
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget('crm_settings');
        \App\Support\SettingsManager::flushCache();

        return $setting;
    }
}
