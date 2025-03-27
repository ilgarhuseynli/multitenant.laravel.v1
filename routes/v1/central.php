<?php

use App\Http\Controllers\Central\AuthController;
use App\Http\Controllers\Central\TenantController;
use Illuminate\Support\Facades\Route;


Route::prefix('central')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:sanctum', 'super-admin'])->group(function () {

        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);


        Route::group(['prefix' => 'tenants', 'as' => 'tenants.'], function () {
            Route::get('', [TenantController::class, 'index']);
            Route::get('/{id}', [TenantController::class, 'show']);
            Route::post('', [TenantController::class, 'store']);
            Route::delete('/{id}', [TenantController::class, 'destroy']);

        });
    });

});

