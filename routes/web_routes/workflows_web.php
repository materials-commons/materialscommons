<?php

use App\Http\Controllers\Web\Workflows\CreateExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\EditExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\StoreExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\UpdateExperimentWorkflowWebController;
use Illuminate\Support\Facades\Route;

Route::patch('/projects/{project}/experiments/{experiment}/workflows/{workflow}/update',
    UpdateExperimentWorkflowWebController::class)
     ->name('projects.experiments.workflows.update');

Route::get('/projects/{project}/experiments/{experiment}/workflows/create',
    CreateExperimentWorkflowWebController::class)
     ->name('projects.experiments.workflows.create');

Route::post('/projects/{project}/experiments/{experiment}/workflows', StoreExperimentWorkflowWebController::class)
     ->name('projects.experiments.workflows.store');

Route::get('/projects/{project}/experiments/{experiment}/workflows/{workflow}/edit',
    EditExperimentWorkflowWebController::class)
     ->name('projects.experiments.workflows.edit');

