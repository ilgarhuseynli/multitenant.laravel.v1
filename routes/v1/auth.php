<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TenantSessionController;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;




//POST /api/login - Login with email/password, optional tenant_id
//GET /api/tenants/{email} - Get available tenants for email
//POST /api/tenant/select - Switch to a different tenant (returns new token)
//GET /api/tenant/current - Get current tenant information
//POST /api/logout - Logout and revoke token


Route::middleware('guest')->group(function () {

    // Public routes
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'index']);
    Route::get('/tenants/list', [TenantSessionController::class, 'getAvailableTenants']);

});



// Add your other authenticated routes here
// These will use the selected tenant's database based on the token
Route::middleware([InitializeTenancyByRequestData::class,])->group(function () {

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Tenant selection
        Route::post('/tenants/select', [TenantSessionController::class, 'selectTenant']);
        Route::get('/tenants/current', [TenantSessionController::class, 'getCurrentTenant']);


        Route::post('/user', [UserController::class, 'user']);
        Route::post('/settings', [UserController::class, 'settings']);
        Route::post('/logout', [LogoutController::class, 'index']);
    });

});


