<?php

namespace App\Listeners;

use App\Events\SettingsChanged;
use App\Support\SettingsManager;
use Illuminate\Support\Facades\Cache;

class FlushSettingsCache
{
    public function handle(SettingsChanged $event): void
    {
        Cache::forget('crm_settings');
        SettingsManager::flushCache();
        SettingsManager::applyRuntimeConfig();
    }
}
