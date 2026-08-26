<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PWA Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the Progressive Web App (PWA) system.
    |
    */

    'name' => env('PWA_NAME', 'InnovaCRM'),

    'short_name' => env('PWA_SHORT_NAME', 'InnovaCRM'),

    'description' => env('PWA_DESCRIPTION', 'InnovaCRM Customer Relationship Management Application'),

    'theme_color' => env('PWA_THEME_COLOR', '#4f46e5'),

    'background_color' => env('PWA_BACKGROUND_COLOR', '#0f172a'),

    'start_url' => env('PWA_START_URL', '/'),

    'scope' => env('PWA_SCOPE', '/'),

    'display' => env('PWA_DISPLAY', 'standalone'),

    'orientation' => env('PWA_ORIENTATION', 'any'),

    'cache_version' => env('PWA_CACHE_VERSION', 'v1.0.0'),

    'dismissal_days' => env('PWA_DISMISSAL_DAYS', 7),

    'enable_install_prompt' => env('PWA_ENABLE_INSTALL_PROMPT', true),

    'enable_offline' => env('PWA_ENABLE_OFFLINE', true),

    'offline_route' => '/offline',
];
