<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TenantSessionController;
use Illuminate\Support\Facades\Route;


//POST /api/login - Login with email/password, optional tenant_id
//GET /api/tenants/{email} - Get available tenants for email
//POST /api/tenant/select - Switch to a different tenant (returns new token)
//GET /api/tenant/current - Get current tenant information
//POST /api/logout - Logout and revoke token


Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/tenants/{email}', [TenantSessionController::class, 'getAvailableTenants']);

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);

        // Tenant selection
        Route::post('/tenant/select', [TenantSessionController::class, 'selectTenant']);
        Route::get('/tenant/current', [TenantSessionController::class, 'getCurrentTenant']);




        // Add your other authenticated routes here
        // These will use the selected tenant's database based on the token
        Route::middleware(['initialize.tenancy'])->group(function () {
            // Your tenant-specific routes go here
            // For example:
            // Route::apiResource('users', UserController::class);
            // Route::apiResource('orders', OrderController::class);
        });


    });


});





// Include your v1 API routes
//Route::prefix('v1')->group(function () {
//    require __DIR__.'/v1/auth.php';
//    require __DIR__.'/v1/user.php';
//});

