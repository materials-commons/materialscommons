<?php

use App\Http\Controllers\Web\Attributes\ShowActivityAttributeDetailsByNameWebController;
use App\Http\Controllers\Web\Attributes\ShowExperimentActivityAttributeWebController;
use App\Http\Controllers\Web\Attributes\ShowExperimentEntityAttributeWebController;
use App\Http\Controllers\Web\Attributes\ShowProjectActivityAttributeWebController;
use App\Http\Controllers\Web\Attributes\ShowProjectEntityAttributeWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('/projects/{project}')->group(function () {
    Route::get('/entity-attributes/show', ShowProjectEntityAttributeWebController::class)
         ->name('projects.entity-attributes.show');
    Route::get('/activity-attributes/show', ShowProjectActivityAttributeWebController::class)
         ->name('projects.activity-attributes.show');
});

Route::prefix('/projects/{project}/experiments/{experiment}')->group(function () {
    Route::get('/entity-attributes/show', ShowExperimentEntityAttributeWebController::class)
         ->name('projects.experiments.entity-attributes.show');
    Route::get('/activity-attributes/show', ShowExperimentActivityAttributeWebController::class)
         ->name('projects.experiments.activity-attributes.show');
});

Route::get('/projects/{project}/activities/attributes/{name}/show-details-by-name',
    ShowActivityAttributeDetailsByNameWebController::class)
     ->name('projects.activities.attributes.show-details-by-name');
//Route::get('/projects/{project}/attributes-by-name/{name}/close-details-by-name',
//    CloseAttributeDetailsByNameWebController::class)
//     ->name('projects.attributes.close-details-by-name');

Route::view('/projects/{project}/attributes-by-name/{name}/close-details-by-name',
    'partials.attributes._close-show-details')
     ->name('projects.attributes.close-details-by-name');

