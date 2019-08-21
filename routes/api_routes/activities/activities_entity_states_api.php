<?php

use App\Http\Controllers\Api\Activities\EntityStates\AddEntityStatesToActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\AddEntityStateToActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\DeleteEntityStateFromActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\DeleteEntityStatesFromActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\ShowEntityStatesInActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\UpdateEntityStateInActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\UpdateEntityStatesInActivityApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('activities/{activity}')->group(function() {
     Route::post('/add-entity-states', AddEntityStatesToActivityApiController::class);

     Route::post('/add-entity-state/{entity_state}',AddEntityStateToActivityApiController::class);

     Route::post('/delete-entity-states', DeleteEntityStatesFromActivityApiController::class);

     Route::post('/delete-entity-state/{entity_state}', DeleteEntityStateFromActivityApiController::class);

     Route::put('/update-entity-states', UpdateEntityStatesInActivityApiController::class);

     Route::put('/update-entity-state/{entity_state}', UpdateEntityStateInActivityApiController::class);

     Route::get('/entity-states', ShowEntityStatesInActivityApiController::class);
});
