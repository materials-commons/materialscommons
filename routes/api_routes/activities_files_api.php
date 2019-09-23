<?php

use App\Http\Controllers\Api\Activities\Files\AddFilesToActivityApiController;
use App\Http\Controllers\Api\Activities\Files\DeleteFilesFromActivityApiController;
use App\Http\Controllers\Api\Activities\Files\ShowFilesInActivityApiController;
use App\Http\Controllers\Api\Activities\Files\UpdateFilesInActivityApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('activities/{activity}')->group(function() {
    Route::post('/add-files', AddFilesToActivityApiController::class);
    Route::post('/deletes-files', DeleteFilesFromActivityApiController::class);
    Route::put('/update-files', UpdateFilesInActivityApiController::class);
    Route::get('/files', ShowFilesInActivityApiController::class);
});
