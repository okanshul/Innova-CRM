<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'status',
        'credentials',
        'connected_at',
    ];

    protected $casts = [
        'credentials' => 'encrypted:array',
        'connected_at' => 'datetime',
    ];

    public static function isConnected(string $provider): bool
    {
        return static::where('provider', $provider)->where('status', 'connected')->exists();
    }
}
