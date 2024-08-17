<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KundeController;


Route::apiResource('kunde', KundeController::class);

// Rate limit für die API
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/kunde', [KundeController::class, 'index']);
    Route::post('/kunde', [KundeController::class, 'store']);
    Route::get('/kunde/{id}', [KundeController::class, 'show']);
    Route::put('/kunde/{id}', [KundeController::class, 'update']);
    Route::delete('/kunde/{id}', [KundeController::class, 'destroy']);
});