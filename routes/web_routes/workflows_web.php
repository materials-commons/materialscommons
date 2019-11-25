<?php

use App\Http\Controllers\Web\Workflows\CreateWorkflowWebController;
use App\Http\Controllers\Web\Workflows\EditWorkflowWebController;
use App\Http\Controllers\Web\Workflows\StoreWorkflowWebController;
use App\Http\Controllers\Web\Workflows\UpdateWorkflowWebController;
use Illuminate\Support\Facades\Route;

Route::patch('/projects/{project}/experiments/{experiment}/workflows/{workflow}/update',
    UpdateWorkflowWebController::class)
     ->name('projects.experiments.workflows.update');

Route::get('/projects/{project}/experiments/{experiment}/workflows/create', CreateWorkflowWebController::class)
     ->name('projects.experiments.workflows.create');

Route::post('/projects/{project}/experiments/{experiment}/workflows', StoreWorkflowWebController::class)
     ->name('projects.experiments.workflows.store');

Route::get('/projects/{project}/experiments/{experiment}/workflows/{workflow}/edit', EditWorkflowWebController::class)
     ->name('projects.experiments.workflows.edit');

