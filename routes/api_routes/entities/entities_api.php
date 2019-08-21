<?php

use App\Http\Controllers\Api\Entities\CreateEntityApiController;
use App\Http\Controllers\Api\Entities\DeleteEntityApiController;
use App\Http\Controllers\Api\Entities\ShowEntityApiController;
use App\Http\Controllers\Api\Entities\UpdateEntityApiController;
use Illuminate\Support\Facades\Route;

Route::post('/entities', CreateEntityApiController::class);
Route::put('/entities/{entity}', UpdateEntityApiController::class);
Route::delete('/entitites/{entity}', DeleteEntityApiController::class);
Route::get('/entities/{entity}', ShowEntityApiController::class);
