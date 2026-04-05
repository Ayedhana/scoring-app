<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompteController;
use App\Http\Controllers\Api\ScoreController;

Route::middleware(['auth:api'])->group(function () {
    Route::get('clients', [ClientController::class, 'getAllClients']);
    Route::get('clients/{id}', [ClientController::class, 'getClientDetails']);
    Route::get('comptes', [CompteController::class, 'getAllComptes']);
    Route::get('comptes/{id}', [CompteController::class, 'getCompteDetails']);
});

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::get('clients', [ClientController::class, 'index']);
        Route::get('clients/{client}', [ClientController::class, 'show']);
        Route::post('clients/{client}/sync', [ClientController::class, 'syncFromT24']);

        Route::get('scores', [ScoreController::class, 'index'])->middleware('role:analyst,admin');
        Route::get('scores/{score}', [ScoreController::class, 'show'])->middleware('role:analyst,admin');
        Route::get('clients/{client}/scores', [ScoreController::class, 'history'])->middleware('role:analyst,admin');
        Route::post('scores/calculate', [ScoreController::class, 'calculate'])->middleware('role:analyst,admin');
    });
});

