<?php

use App\Http\Controllers\Web\Workflows\UpdateWorkflowWebController;
use Illuminate\Support\Facades\Route;

Route::patch('/projects/{project}/experiments/{experiment}/workflows/{workflow}/update',
    UpdateWorkflowWebController::class)
     ->name('projects.experiments.workflows.update');

