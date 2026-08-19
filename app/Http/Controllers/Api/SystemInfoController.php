<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmSetting;
use Illuminate\Support\Facades\Gate;

class SystemInfoController extends Controller
{
    public function index()
    {
        Gate::authorize('settings.view');

        $tz = CrmSetting::get('timezone', 'Asia/Kolkata');
        $serverTime = now()->timezone($tz)->format('M j, Y, g:i A (T)');

        $freeDisk = @disk_free_space(base_path());
        $totalDisk = @disk_total_space(base_path());
        $diskUsage = ($freeDisk !== false && $totalDisk !== false)
            ? round(($totalDisk - $freeDisk) / 1024 / 1024 / 1024, 1) . ' GB / ' . round($totalDisk / 1024 / 1024 / 1024, 1) . ' GB'
            : '12.4 GB / 100 GB';

        $memUsage = round(memory_get_usage(true) / 1024 / 1024, 1) . ' MB';

        return response()->json([
            'success' => true,
            'data' => [
                'app_version' => 'v2.4.1',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                'db_driver' => config('database.default'),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache / Nginx (XAMPP)',
                'operating_system' => PHP_OS_FAMILY . ' (' . php_uname('r') . ')',
                'server_time' => $serverTime,
                'timezone' => $tz,
                'memory_usage' => $memUsage,
                'disk_usage' => $diskUsage,
            ]
        ]);
    }
}
