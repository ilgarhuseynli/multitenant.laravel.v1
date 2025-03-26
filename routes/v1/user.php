<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum', 'auth.gates']], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('minlist', [UsersController::class,'minlist']);
        Route::post('{user}/update-password', [UsersController::class,'updatePassword']);
    });

    Route::apiResource('users', UsersController::class);

});
