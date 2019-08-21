<?php

use App\Http\Controllers\Api\Activities\Attributes\AddAttributesToActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\AddAttributeToActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\DeleteAttributeFromActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\DeleteAttributesFromActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\ShowAttributesInActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\UpdateAttributeInActivityApiController;
use App\Http\Controllers\Api\Activities\Attributes\UpdateAttributesInActivityApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('activities/{activity}')->group(function() {
    Route::post('/add-attributes', AddAttributesToActivityApiController::class);

    Route::post('/add-attribute', AddAttributeToActivityApiController::class);

    Route::post('/delete-attributes', DeleteAttributesFromActivityApiController::class);

    Route::post('/delete-attribute/{attribute}', DeleteAttributeFromActivityApiController::class);

    Route::put('/update-attributes', UpdateAttributesInActivityApiController::class);

    Route::put('/update-attribute/{attribute}', UpdateAttributeInActivityApiController::class);

    Route::get('/attributes', ShowAttributesInActivityApiController::class);
});

