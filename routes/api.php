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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth'])->name('crm.')->group(function () {
    // Staff API
    Route::get('staff/{id}/permissions', [StaffController::class, 'getPermissions'])->name('staff.permissions.get');
    Route::put('staff/{id}/permissions', [StaffController::class, 'updatePermissions'])->name('staff.permissions.update');
    Route::apiResource('staff', StaffController::class)->names('api.staff');

    // Contacts API
    Route::apiResource('contacts', ContactController::class)->names('api.contacts');

    // Deals API
    Route::apiResource('deals', DealController::class)->names('api.deals');

    // Pipelines API
    Route::apiResource('pipelines', PipelineController::class)->names('api.pipelines');

    // Tasks API
    Route::apiResource('tasks', TaskController::class)->names('api.tasks');

    // Meetings API
    Route::apiResource('meetings', MeetingController::class)->names('api.meetings');

    // Roles API
    Route::apiResource('roles', RoleController::class)->names('api.roles');

    // Settings API
    Route::get('settings', [SettingController::class, 'index'])->name('api.settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('api.settings.store');

    // Profile API
    Route::get('profile', [\App\Http\Controllers\Api\ProfileController::class, 'show'])->name('api.profile.show');
    Route::post('profile', [\App\Http\Controllers\Api\ProfileController::class, 'update'])->name('api.profile.update');
    Route::put('profile/password', [\App\Http\Controllers\Api\ProfileController::class, 'updatePassword'])->name('api.profile.password');
});
