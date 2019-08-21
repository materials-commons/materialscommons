<?php

use App\Http\Controllers\Api\Activities\Files\AddFilesToActivityApiController;
use App\Http\Controllers\Api\Activities\Files\AddFileToActivityApiController;
use App\Http\Controllers\Api\Activities\Files\DeleteFileFromActivityApiController;
use App\Http\Controllers\Api\Activities\Files\DeleteFilesFromActivityApiController;
use App\Http\Controllers\Api\Activities\Files\ShowFilesInActivityApiController;
use App\Http\Controllers\Api\Activities\Files\UpdateFileInActivityApiController;
use App\Http\Controllers\Api\Activities\Files\UpdateFilesInActivityApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('activities/{activity}')->group(function() {
    Route::post('/add-files', AddFilesToActivityApiController::class);

    Route::post('/add-file/{file}', AddFileToActivityApiController::class);

    Route::post('/deletes-files', DeleteFilesFromActivityApiController::class);

    Route::post('/delete-file/{file}', DeleteFileFromActivityApiController::class);

    Route::put('/update-files', UpdateFilesInActivityApiController::class);

    Route::put('/update-file/{file}', UpdateFileInActivityApiController::class);

    Route::get('/files', ShowFilesInActivityApiController::class);
});
