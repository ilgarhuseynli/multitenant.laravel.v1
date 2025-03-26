<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;



foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes

        Route::group(['prefix' => 'tenants','as' => 'tenants.'], function () {
            Route::get('/store', [TenantController::class, 'store'])->name('store');
            Route::get('/destroy/{id}', [TenantController::class, 'destroy'])->name('destroy');
        });

        Route::get('/', function () {
            return view('welcome');
        });

        Route::get('login', [LoginController::class,'showLogin'])->name('login');

    });
}
