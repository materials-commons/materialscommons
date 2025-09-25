<?php

use App\Http\Controllers\Api\Experiments\CreateExperimentApiController;
use App\Http\Controllers\Api\Experiments\DeleteExperimentApiController;
use App\Http\Controllers\Api\Experiments\IndexExperimentsApiController;
use App\Http\Controllers\Api\Experiments\ReloadExperimentFromGoogleSheetApiController;
use App\Http\Controllers\Api\Experiments\ShowExperimentApiController;
use App\Http\Controllers\Api\Experiments\UpdateExperimentApiController;
use App\Http\Controllers\Api\Experiments\Workflows\UpdateExperimentWorkflowSelectionApiController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/experiments', IndexExperimentsApiController::class);
Route::post('/experiments', CreateExperimentApiController::class);
Route::put('/experiments/{experiment}', UpdateExperimentApiController::class);
Route::delete('/projects/{project}/experiments/{experiment}', DeleteExperimentApiController::class)
     ->name('api.projects.experiments.delete');
Route::get('/experiments/{experiment}', ShowExperimentApiController::class);

Route::put('/experiments/{experiment}/workflows/selection', UpdateExperimentWorkflowSelectionApiController::class)
     ->name('api.projects.experiments.workflows.selection');

Route::post('/google-sheets/reload-experiment', ReloadExperimentFromGoogleSheetApiController::class);
