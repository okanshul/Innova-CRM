
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/offline', function () {
    return view('errors.offline');
})->name('offline');


// Password Reset Routes (Guest Middleware)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetOtp'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.submit');
    Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend')->middleware('throttle:5,1');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// PWA Web Manifest routes (ensures application/manifest+json Content-Type on shared hosts)
Route::get('/manifest.webmanifest', function () {
    $path = public_path('manifest.webmanifest');
    if (!file_exists($path)) {
        $path = public_path('manifest.json');
    }
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/manifest+json',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('pwa.manifest');

Route::get('/manifest.json', function () {
    $path = public_path('manifest.json');
    if (!file_exists($path)) {
        $path = public_path('manifest.webmanifest');
    }
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/manifest+json',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});

// Serve public storage files fallback (for shared hosting where symlinks are restricted)
Route::get('/storage/{path}', function (string $path) {
    $diskPath = storage_path('app/public/' . $path);
    $realStoragePath = realpath(storage_path('app/public'));
    $realFilePath = realpath($diskPath);

    if (!$realFilePath || !$realStoragePath || !str_starts_with($realFilePath, $realStoragePath) || !file_exists($realFilePath)) {
        if (str_starts_with($path, 'avatars/')) {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/></svg>';
            return response($svg, 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'public, max-age=3600']);
        }
        abort(404);
    }

    return response()->file($realFilePath);
})->where('path', '.*')->name('storage.file');


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::resource('staff', StaffController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('contacts', ContactController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('deals', DealController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('pipelines', PipelineController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('reports', ReportController::class)->only(['index']);
    Route::resource('tasks', TaskController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('calendar', CalendarController::class)->only(['index']);
    Route::resource('meetings', MeetingController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('mail', MailController::class)->only(['index']);
    Route::resource('roles', RoleController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('settings', SettingController::class)->only(['index']);
});
