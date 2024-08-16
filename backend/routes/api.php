<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KundeController;


Route::apiResource('kunde', KundeController::class);