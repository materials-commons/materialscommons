<?php

use App\Http\Controllers\Web\Workflows\CreateExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\CreateProjectWorkflowWebController;
use App\Http\Controllers\Web\Workflows\DeleteProjectWorkflowWebController;
use App\Http\Controllers\Web\Workflows\DestroyProjectWorkflowWebController;
use App\Http\Controllers\Web\Workflows\EditExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\EditProjectWorkflowWebController;
use App\Http\Controllers\Web\Workflows\IndexProjectWorkflowsWebController;
use App\Http\Controllers\Web\Workflows\StoreExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\StoreProjectWorkflowWebController;
use App\Http\Controllers\Web\Workflows\UpdateExperimentWorkflowWebController;
use App\Http\Controllers\Web\Workflows\UpdateProjectWorkflowWebController;
use Illuminate\Support\Facades\Route;

// Experiment level workflows

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

// Project level workflows

Route::get('/projects/{project}/workflows', IndexProjectWorkflowsWebController::class)
     ->name('projects.workflows.index');

Route::get('/projects/{project}/workflows/create', CreateProjectWorkflowWebController::class)
     ->name('projects.workflows.create');

Route::post('/projects/{project}/workflows', StoreProjectWorkflowWebController::class)
     ->name('projects.workflows.store');

Route::get('/projects/{project}/workflows/{workflow}/edit', EditProjectWorkflowWebController::class)
     ->name('projects.workflows.edit');

Route::put('/projects/{project}/workflows/{workflow}', UpdateProjectWorkflowWebController::class)
     ->name('projects.workflows.update');

Route::get('/projects/{project}/workflows/{workflow}/delete', DeleteProjectWorkflowWebController::class)
     ->name('projects.workflows.delete');

Route::delete('/projects/{project}/workflows/{workflow}', DestroyProjectWorkflowWebController::class)
     ->name('projects.workflow.destroy');


