<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\ApplicationClinicalAuthorizationController;
use App\Http\Controllers\Dashboard\ApproveController;
use App\Http\Controllers\Dashboard\CertificateController;
use App\Http\Controllers\Dashboard\CredentialController;
use App\Http\Controllers\Dashboard\FileController;
use App\Http\Controllers\Dashboard\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentGroupController;
use App\Http\Controllers\TournamentParticipantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('login');
});
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'destroy');
});
Route::middleware('already.login')->group(function () {
    Route::prefix('login')->group(function () {
        Route::controller(LoginController::class)->group(function () {
            Route::get('/', 'index')->name('login');
            Route::post('/', 'store');
        });
    });
    Route::prefix('register')->group(function () {
        Route::controller(RegisterController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
        });
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('', 'index')->name('home');
        });
    });

    Route::prefix('team')->group(function () {
        Route::controller(TeamController::class)->group(function () {
            Route::get('', 'index');
            Route::get('create', 'create');
            Route::get('{team_slug}', 'detail');
            Route::post('create', 'store');
            Route::get('search', 'search');
        });
        Route::prefix('{team_slug}/member')->group(function () {
            Route::controller(TeamMemberController::class)->group(function () {
                Route::get('create', 'create');
                Route::get('{member_slug}', 'detail');
                Route::post('create', 'store');
            });
        });
    });
    Route::prefix('tournament')->group(function () {
        Route::controller(TournamentController::class)->group(function () {
            Route::get('', 'index');
            Route::get('create', 'create');
            Route::post('create', 'store');
            Route::get('{slug}', 'detail');
            Route::post('create/{slug}', 'store');
        });
        Route::prefix('{tournament_slug}/group')->group(function () {
            Route::controller(TournamentGroupController::class)->group(function () {
                Route::get('create', 'create');
                Route::post('create', 'store');
                Route::get('{slug}', 'detail');
            });
            Route::prefix('{group_slug}/participant')->group(function () {
                Route::controller(TournamentParticipantController::class)->group(function () {
                    Route::get('create', 'create');
                    Route::post('create', 'store');
                    Route::get('{slug}', 'detail');
                });
            });
        });
    });
    Route::prefix('participant')->group(function () {
        Route::controller(ParticipantController::class)->group(function () {
            Route::get('', 'index');
            Route::get('{group_slug}', 'detail');
            Route::get('{group_slug}/competition', 'competition');
            Route::get('{group_slug}/competition/close', 'close_competition');
        });
    });
});
