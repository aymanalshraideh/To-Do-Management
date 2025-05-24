<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\TaskApi\App\Http\Controllers\TaskAPIController;



/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])
    ->group(function () {
        Route::prefix('v1')->group(function () {
    Route::apiResource('tasks', TaskAPIController::class);
      Route::get('/test', function () {
        return 'TaskApi';
    }   );
});

    });

