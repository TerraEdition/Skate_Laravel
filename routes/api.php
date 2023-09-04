<?php

use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\TeamMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('team')->group(function () {
    Route::controller(TeamController::class)->group(function () {
        Route::get('search', 'search');
    });
    Route::prefix('member')->group(function () {
        Route::controller(TeamMemberController::class)->group(function () {
            Route::get('search', 'search');
        });
    });
});
