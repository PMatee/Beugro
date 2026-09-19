<?php

use App\Http\Controllers\PriceController;

use App\Http\Controllers\TimeBlockController;
Route::get('/prices', [PriceController::class, 'index']);
Route::get('/prices/blocks', [PriceController::class, 'blocks']);
Route::get('/time-blocks', [TimeBlockController::class, 'index']);
Route::post('/time-blocks', [TimeBlockController::class, 'store']);
