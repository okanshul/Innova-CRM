<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\PipelineController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\MeetingController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\SystemInfoController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\GlobalSearchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth'])->name('crm.')->group(function () {
    // Staff API
    Route::get('staff/{id}/permissions', [StaffController::class, 'getPermissions'])->name('staff.permissions.get');
    Route::put('staff/{id}/permissions', [StaffController::class, 'updatePermissions'])->name('staff.permissions.update');
    Route::post('staff/bulk-delete', [StaffController::class, 'bulkDestroy'])->name('api.staff.bulk_delete');
    Route::apiResource('staff', StaffController::class)->names('api.staff');

    // Contacts API
    Route::post('contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('api.contacts.bulk_delete');
    Route::apiResource('contacts', ContactController::class)->names('api.contacts');

    // Deals API
    Route::post('deals/bulk-delete', [DealController::class, 'bulkDestroy'])->name('api.deals.bulk_delete');
    Route::apiResource('deals', DealController::class)->names('api.deals');

    // Pipelines API
    Route::apiResource('pipelines', PipelineController::class)->names('api.pipelines');

    // Tasks API
    Route::post('tasks/bulk-delete', [TaskController::class, 'bulkDestroy'])->name('api.tasks.bulk_delete');
    Route::apiResource('tasks', TaskController::class)->names('api.tasks');

    // Meetings API
    Route::post('meetings/bulk-delete', [MeetingController::class, 'bulkDestroy'])->name('api.meetings.bulk_delete');
    Route::apiResource('meetings', MeetingController::class)->names('api.meetings');

    // Roles API
    Route::apiResource('roles', RoleController::class)->names('api.roles');

    // Settings API
    Route::get('settings', [SettingController::class, 'index'])->name('api.settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('api.settings.store');
    Route::post('settings/reset', [SettingController::class, 'reset'])->name('api.settings.reset');
    Route::post('settings/test-email', [SettingController::class, 'testEmail'])->name('api.settings.test_email');

    // Users API
    Route::apiResource('users', UserController::class)->names('api.users');

    // Backups API
    Route::get('backups/{id}/download', [BackupController::class, 'download'])->name('api.backups.download');
    Route::post('backups/restore', [BackupController::class, 'restore'])->name('api.backups.restore');
    Route::apiResource('backups', BackupController::class)->names('api.backups');

    // Audit Logs API
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('api.audit_logs.index');

    // System Info API
    Route::get('system-info', [SystemInfoController::class, 'index'])->name('api.system_info.index');

    // Profile API
    Route::get('profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::post('profile', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('api.profile.password');

    // Global Search API
    Route::get('search', [GlobalSearchController::class, 'search'])->name('api.search');
});
