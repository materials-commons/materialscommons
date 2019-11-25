<?php

use App\Http\Controllers\Api\Datasets\UpdateDatasetEntitySelectionApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetFileSelectionApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetWorkflowSelectionApiController;
use Illuminate\Support\Facades\Route;

Route::put('/datasets/{dataset}/selection', UpdateDatasetFileSelectionApiController::class)
     ->name('projects.datasets.selection');

Route::put('/datasets/{dataset}/entities', UpdateDatasetEntitySelectionApiController::class)
     ->name('api.projects.datasets.entities');

Route::put('/datastes/{dataset}/workflows', UpdateDatasetWorkflowSelectionApiController::class)
     ->name('api.projects.datasets.workflows');

