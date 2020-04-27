<?php

use App\Http\Controllers\Api\Datasets\CreateDatasetApiController;
use App\Http\Controllers\Api\Datasets\DeleteDatasetApiController;
use App\Http\Controllers\Api\Datasets\DownloadDatasetZipfileApiController;
use App\Http\Controllers\Api\Datasets\IndexDatasetsApiController;
use App\Http\Controllers\Api\Datasets\PublishDatasetApiController;
use App\Http\Controllers\Api\Datasets\ShowDatasetApiController;
use App\Http\Controllers\Api\Datasets\UnpublishDatasetApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetActivitySelectionApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetEntitySelectionApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetFileSelectionApiController;
use App\Http\Controllers\Api\Datasets\UpdateDatasetWorkflowSelectionApiController;
use Illuminate\Support\Facades\Route;

Route::put('/datasets/{dataset}/selection', UpdateDatasetFileSelectionApiController::class)
     ->name('projects.datasets.selection');

Route::put('/datasets/{dataset}/entities', UpdateDatasetEntitySelectionApiController::class)
     ->name('api.projects.datasets.entities');

Route::put('/datasets/{dataset}/activities/selection', UpdateDatasetActivitySelectionApiController::class)
     ->name('api.projects.datasets.activities.selection');

Route::put('/datastes/{dataset}/workflows', UpdateDatasetWorkflowSelectionApiController::class)
     ->name('api.projects.datasets.workflows');

Route::get('/projects/{project}/datasets/{dataset}', ShowDatasetApiController::class);
Route::get('/projects/{project}/datasets', IndexDatasetsApiController::class);
Route::put('/datasets/{dataset}', UpdateDatasetApiController::class);
Route::post('/datasets', CreateDatasetApiController::class);
Route::delete('/projects/{project}/datasets/{dataset}', DeleteDatasetApiController::class);
Route::put('/datasets/{dataset}/publish', PublishDatasetApiController::class);
Route::put('/datasets/{dataset}/unpublish', UnpublishDatasetApiController::class);
Route::get('/datasets/{dataset}/download_zipfile', DownloadDatasetZipfileApiController::class);



