<?php

use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return response()->json('test');
});



// Include your v1 API routes
Route::prefix('v1')->group(function () {
    require __DIR__.'/v1/auth.php';
    require __DIR__.'/v1/central.php';
    require __DIR__.'/v1/user.php';
});




