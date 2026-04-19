<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompteController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\RapportController;
use App\Http\Controllers\Api\CritereController;
Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // 🔥 METS CETTE ROUTE ICI (hors auth)
    Route::get('/rapports/stats', [RapportController::class, 'stats']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{id}', [ClientController::class, 'show']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Critères — accessibles par admin seulement
       Route::get('criteres', [CritereController::class, 'index']);
       Route::post('criteres/update-all', [CritereController::class, 'updateAll']); // ← AVANT {critere}
       Route::post('criteres', [CritereController::class, 'store']);
       Route::get('criteres/{critere}', [CritereController::class, 'show']);
       Route::put('criteres/{critere}', [CritereController::class, 'update']);
       Route::delete('criteres/{critere}', [CritereController::class, 'destroy']);
        Route::post('clients/{client}/sync', [ClientController::class, 'syncFromT24']);

        Route::get('scores', [ScoreController::class, 'index']);
        Route::get('scores/{score}', [ScoreController::class, 'show']);
        Route::get('clients/{client}/scores', [ScoreController::class, 'history']);
        Route::post('scores/calculate', [ScoreController::class, 'calculate']);
    });

});