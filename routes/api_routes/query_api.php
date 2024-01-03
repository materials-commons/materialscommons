<?php

use App\Http\Controllers\Api\Query\ExecuteMQLApiController;
use App\Http\Controllers\Api\Query\FindMatchingEntitiesInProjectApiController;
use App\Http\Controllers\Api\Query\LoadMQLDBForProjectApiController;
use App\Http\Controllers\Api\Query\ReloadMQLDBForProjectApiController;
use Illuminate\Support\Facades\Route;

Route::post('/queries/{project}/load-project', LoadMQLDBForProjectApiController::class);
Route::post('/queries/{project}/reload-project', ReloadMQLDBForProjectApiController::class);
Route::post('/queries/{project}/execute-query', ExecuteMQLApiController::class);
Route::post('/queries/{project}/find-matching-entities', FindMatchingEntitiesInProjectApiController::class)
     ->name('api.queries.find-matching-entities');

