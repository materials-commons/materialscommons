<?php

use App\Http\Controllers\Api\Activities\CreateActivityApiController;
use App\Http\Controllers\Api\Entities\CreateEntityApiController;
use App\Http\Controllers\Api\Etl\AddMeasurementsToSampleInProcessApiController;
use App\Http\Controllers\Api\Etl\AddSampleAndFilesToProcessApiController;
use App\Http\Controllers\Api\Etl\GetFileByPathInProjectApiController;
use App\Http\Controllers\Api\Etl\UpdateExperimentProgressStatusApiController;
use App\Http\Controllers\Api\Experiments\CreateExperimentApiController;
use App\Http\Controllers\Api\Projects\CreateProjectApiController;
use Illuminate\Support\Facades\Route;

Route::post('/etl/getFileByPathInProject',
    GetFileByPathInProjectApiController::class)->name('api.etl.getFileByPathInProject');

// Add a second path to create a project that matches the expected path for the ETL api
Route::post('/etl/createProject', CreateProjectApiController::class)->name('api.etl.createProject');

// Add a second path to create an experiment that matches the expected path for the ETL api
Route::post('/etl/createExperimentInProject',
    CreateExperimentApiController::class)->name('api.etl.createExperimentInProject');

Route::post('/etl/updateExperimentProgressStatus',
    UpdateExperimentProgressStatusApiController::class)->name('api.etl.updateExperimentProgressStatus');

// Add a second path to create an entity that matches the expected path for the ETL api
Route::post('/etl/createSample', CreateEntityApiController::class)->name('api.etl.createSample');

// Add a second path to create an activity more closely matches the expected for the ETL api
Route::post('/etl/createProcess', CreateActivityApiController::class)->name('api.etl.createProcess');

Route::post('/etl/addSampleAndFilesToProcess',
    AddSampleAndFilesToProcessApiController::class)->name('api.etl.addSampleAndFilesToProcess');

Route::post('/etl/addMeasurementsToSampleInProcess',
    AddMeasurementsToSampleInProcessApiController::class)->name('api.etl.addMeasurementsToSampleInProcess');