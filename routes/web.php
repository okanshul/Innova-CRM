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
