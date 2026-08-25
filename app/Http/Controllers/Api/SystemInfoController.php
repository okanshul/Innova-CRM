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

        $freeDisk = function_exists('disk_free_space') ? @disk_free_space(base_path()) : false;
        $totalDisk = function_exists('disk_total_space') ? @disk_total_space(base_path()) : false;
        $diskUsage = ($freeDisk !== false && $totalDisk !== false && $totalDisk > 0)
            ? round(($totalDisk - $freeDisk) / 1024 / 1024 / 1024, 1) . ' GB / ' . round($totalDisk / 1024 / 1024 / 1024, 1) . ' GB'
            : 'N/A';

        $memUsage = function_exists('memory_get_usage') ? round(memory_get_usage(true) / 1024 / 1024, 1) . ' MB' : 'N/A';
        $osRelease = function_exists('php_uname') ? @php_uname('r') : '';
        $operatingSystem = PHP_OS_FAMILY . ($osRelease !== '' ? ' (' . $osRelease . ')' : '');

        return response()->json([
            'success' => true,
            'data' => [
                'app_version' => 'v2.4.1',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                'db_driver' => config('database.default'),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache / Nginx',
                'operating_system' => $operatingSystem,
                'server_time' => $serverTime,
                'timezone' => $tz,
                'memory_usage' => $memUsage,
                'disk_usage' => $diskUsage,
            ]
        ]);
    }
}
