<?php

use App\Http\Controllers\Api\Experiments\CreateExperimentApiController;
use App\Http\Controllers\Api\Experiments\DeleteExperimentApiController;
use App\Http\Controllers\Api\Experiments\ShowExperimentApiController;
use App\Http\Controllers\Api\Experiments\UpdateExperimentApiController;
use Illuminate\Support\Facades\Route;

Route::post('/experiments', CreateExperimentApiController::class);
Route::put('/experiments/{experiment}', UpdateExperimentApiController::class);
Route::delete('/experiments/{experiment}', DeleteExperimentApiController::class);
Route::get('/experiments/{experiment}', ShowExperimentApiController::class);