<?php

use App\Http\Controllers\Api\Directories\CreateDirectoryApiController;
use App\Http\Controllers\Api\Directories\CreateDirectoryByPathApiController;
use App\Http\Controllers\Api\Directories\DeleteDirectoryApiController;
use App\Http\Controllers\Api\Directories\IndexDirectoryApiController;
use App\Http\Controllers\Api\Directories\IndexDirectoryByPathApiController;
use App\Http\Controllers\Api\Directories\MoveDirectoryApiController;
use App\Http\Controllers\Api\Directories\RenameDirectoryApiController;
use App\Http\Controllers\Api\Directories\ShowDirectoryApiController;
use App\Http\Controllers\Api\Directories\UpdateDirectoryApiController;
use Illuminate\Support\Facades\Route;

Route::post('/directories', CreateDirectoryApiController::class);
Route::post("/directories/by-path", CreateDirectoryByPathApiController::class);
Route::put('/directories/{directory}', UpdateDirectoryApiController::class);
Route::delete('/projects/{project}/directories/{directory}', DeleteDirectoryApiController::class);
Route::get('/projects/{project}/directories/{directory}', ShowDirectoryApiController::class);
Route::post('/directories/{directory}/move', MoveDirectoryApiController::class);
Route::post('/directories/{directory}/rename', RenameDirectoryApiController::class);
Route::get('/projects/{project}/directories_by_path', IndexDirectoryByPathApiController::class);
Route::get('/projects/{project}/directories/{directory}/list', IndexDirectoryApiController::class);
