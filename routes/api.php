<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('staff/{id}/permissions', [StaffController::class, 'getPermissions']);
    Route::put('staff/{id}/permissions', [StaffController::class, 'updatePermissions']);
    Route::apiResource('staff', StaffController::class)->names('api.staff');
});
