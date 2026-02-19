<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — No authentication required
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes — Must be authenticated (valid Sanctum token)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth actions that require being logged in
    Route::prefix('auth')->group(function () {
        Route::post('/logout',          [AuthController::class, 'logout']);
        Route::get('/me',               [AuthController::class, 'me']);
        Route::put('/change-password',  [AuthController::class, 'changePassword']);
    });

    /*
    |----------------------------------------------------------------------
    | Admin-only routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // will fill this later
    });

    /*
    |----------------------------------------------------------------------
    | Staff routes — accessible by staff AND admin
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,staff')->prefix('staff')->group(function () {
        // will fill this later
    });

    /*
    |----------------------------------------------------------------------
    | Client routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:client,admin')->prefix('client')->group(function () {
        // will fill this later
    });


});