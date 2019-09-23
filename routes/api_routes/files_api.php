<?php

use App\Http\Controllers\Api\Files\CreateFileApiController;
use App\Http\Controllers\Api\Files\DeleteFileApiController;
use App\Http\Controllers\Api\Files\MoveFileApiController;
use App\Http\Controllers\Api\Files\RenameFileApiController;
use App\Http\Controllers\Api\Files\ShowFileApiController;
use App\Http\Controllers\Api\Files\UpdateFileApiController;
use Illuminate\Support\Facades\Route;

Route::post('/files', CreateFileApiController::class);
Route::put('/files/{file}', UpdateFileApiController::class);
Route::delete('/files/{file}', DeleteFileApiController::class);
Route::get('/files/{file}', ShowFileApiController::class);
Route::post('/files/{file}/move', MoveFileApiController::class);
Route::post('/files/{file}/rename', RenameFileApiController::class);