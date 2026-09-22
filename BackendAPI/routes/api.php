<?php

use App\Http\Controllers\PriceController;
use App\Http\Controllers\TimeBlockController;
use App\Http\Controllers\ConsumptionController;

Route::get('/prices', [PriceController::class, 'index']);
Route::get('/time-blocks', [TimeBlockController::class, 'index']);
Route::post('/time-blocks', [TimeBlockController::class, 'store']);
Route::get('/consumption', [ConsumptionController::class, 'index']);