<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if the application is in maintenance mode
if (file_exists($maintenance = __DIR__ . '/laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Load Composer autoloader
require __DIR__ . '/laravel/vendor/autoload.php';

// Bootstrap Laravel application
/** @var Application $app */
$app = require_once __DIR__ . '/laravel/bootstrap/app.php';

// Set Laravel public path to InfinityFree htdocs
$app->usePublicPath(__DIR__);

// Handle incoming request
$app->handleRequest(Request::capture());
