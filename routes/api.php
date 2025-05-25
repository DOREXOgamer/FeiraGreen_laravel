<?php

use App\Http\Controllers\Api\ProdutoApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('produtos')->group(function () {
    Route::get('/', [ProdutoApiController::class, 'index']);
    Route::get('/{id}', [ProdutoApiController::class, 'show']);
    Route::post('/', [ProdutoApiController::class, 'store']);
    Route::put('/{id}', [ProdutoApiController::class, 'update']);
    Route::delete('/{id}', [ProdutoApiController::class, 'destroy']);
});
