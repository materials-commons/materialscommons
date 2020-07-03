<?php

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

