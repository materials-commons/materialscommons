<?php

use App\Http\Controllers\Api\Datasets\UpdateDatasetFileSelectionApiController;
use Illuminate\Support\Facades\Route;

Route::put('/datasets/{dataset}/selection', UpdateDatasetFileSelectionApiController::class)
     ->name('projects.datasets.selection');

