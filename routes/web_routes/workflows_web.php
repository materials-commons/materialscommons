<?php

use App\Http\Controllers\Web\Workflows\CreateWorkflowWebController;
use App\Http\Controllers\Web\Workflows\UpdateWorkflowWebController;
use Illuminate\Support\Facades\Route;

Route::patch('/projects/{project}/experiments/{experiment}/workflows/{workflow}/update',
    UpdateWorkflowWebController::class)
     ->name('projects.experiments.workflows.update');

Route::get('projects/{project}/experiments/{experiment}/workflows/create', CreateWorkflowWebController::class)
     ->name('projects.experiments.workflows.create');

