<?php

use App\Http\Controllers\Api\Files\CreateDirectoryApiController;
use App\Http\Controllers\Api\Files\CreateFileApiController;
use App\Http\Controllers\Api\Files\DeleteDirectoryApiController;
use App\Http\Controllers\Api\Files\DeleteFileApiController;
use App\Http\Controllers\Api\Files\ShowDirectoryApiController;
use App\Http\Controllers\Api\Files\ShowFileApiController;
use App\Http\Controllers\Api\Files\UpdateDirectoryApiController;
use App\Http\Controllers\Api\Files\UpdateFileApiController;
use Illuminate\Support\Facades\Route;

Route::post('/files', CreateFileApiController::class);
Route::put('/files/{file}', UpdateFileApiController::class);
Route::delete('/files/{file}', DeleteFileApiController::class);
Route::get('/files/{file}', ShowFileApiController::class);

Route::post('/directories', CreateDirectoryApiController::class);
Route::put('/directories/{directory}', UpdateDirectoryApiController::class);
Route::delete('/directories/{directory}', DeleteDirectoryApiController::class);
Route::get('/directories/{directory}', ShowDirectoryApiController::class);