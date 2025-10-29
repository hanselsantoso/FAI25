<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::prefix('demo')->name('demo.')->group(function () {
    Route::apiResource('products', ProductApiController::class)->names('products');
});
