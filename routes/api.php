<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Central\AuthController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\TenantSessionController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;


//POST /api/login - Login with email/password, optional tenant_id
//GET /api/tenants/{email} - Get available tenants for email
//POST /api/tenant/select - Switch to a different tenant (returns new token)
//GET /api/tenant/current - Get current tenant information
//POST /api/logout - Logout and revoke token


Route::get('/test', function () {

    dd(Hash::make('12345678'));

});

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'index']);
    Route::get('/public/tenants/{email}', [TenantSessionController::class, 'getAvailableTenants']);


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


});




//foreach (config('tenancy.central_domains') as $domain) {
//    Route::domain($domain)->group(function () {
//        // your actual routes
//
//    });
//}


// Include your v1 API routes
//Route::prefix('v1')->group(function () {
//    require __DIR__.'/v1/auth.php';
//    require __DIR__.'/v1/user.php';
//});

