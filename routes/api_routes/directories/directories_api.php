<?php

use App\Http\Controllers\Api\Directories\CreateDirectoryApiController;
use App\Http\Controllers\Api\Directories\DeleteDirectoryApiController;
use App\Http\Controllers\Api\Directories\ShowDirectoryApiController;
use App\Http\Controllers\Api\Directories\UpdateDirectoryApiController;
use Illuminate\Support\Facades\Route;

Route::post('/directories', CreateDirectoryApiController::class);
Route::put('/directories/{directory}', UpdateDirectoryApiController::class);
Route::delete('/directories/{directory}', DeleteDirectoryApiController::class);
Route::get('/directories/{directory}', ShowDirectoryApiController::class);