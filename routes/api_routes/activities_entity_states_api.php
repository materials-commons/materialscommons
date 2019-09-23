<?php

use App\Http\Controllers\Api\Activities\EntityStates\AddEntityStatesToActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\DeleteEntityStatesFromActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\ShowEntityStatesInActivityApiController;
use App\Http\Controllers\Api\Activities\EntityStates\UpdateEntityStatesInActivityApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('activities/{activity}')->group(function() {
     Route::post('/add-entity-states', AddEntityStatesToActivityApiController::class);
     Route::post('/delete-entity-states', DeleteEntityStatesFromActivityApiController::class);
     Route::put('/update-entity-states', UpdateEntityStatesInActivityApiController::class);
     Route::get('/entity-states', ShowEntityStatesInActivityApiController::class);
});
