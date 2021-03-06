<?php

use App\Http\Controllers\Api\Datasets\AssignDoiApiController;
use App\Http\Controllers\Api\Datasets\ChangeDatasetFileSelectionApiController;
use App\Http\Controllers\Api\Datasets\CreateDatasetApiController;
use App\Http\Controllers\Api\Datasets\DeleteDatasetApiController;
use App\Http\Controllers\Api\Datasets\FileByPathInDatasetSelectionApiController;
use App\Http\Controllers\Api\Datasets\FileInDatasetSelectionApiController;
use App\Http\Controllers\Api\Datasets\ImportDatasetIntoProjectApiController;
use App\Http\Controllers\Api\Datasets\IndexDatasetActivitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexDatasetEntitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexDatasetFilesApiController;
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

Route::put('/datasets/{dataset}/workflows', UpdateDatasetWorkflowSelectionApiController::class)
     ->name('api.projects.datasets.workflows');

Route::put('/projects/{project}/datasets/{dataset}/assign_doi', AssignDoiApiController::class);

Route::get('/projects/{project}/datasets/{dataset}', ShowDatasetApiController::class)
     ->name('api.projects.datasets.show');

Route::get('/projects/{project}/datasets/{dataset}/files', IndexDatasetFilesApiController::class);
Route::get('/projects/{project}/datasets/{dataset}/entities', IndexDatasetEntitiesApiController::class);
Route::get('/projects/{project}/datasets/{dataset}/activities', IndexDatasetActivitiesApiController::class);

Route::get('/projects/{project}/datasets', IndexDatasetsApiController::class)
     ->name('api.projects.datasets.index');

Route::put('/projects/{project}/datasets/{dataset}', UpdateDatasetApiController::class);
Route::post('/projects/{project}/datasets', CreateDatasetApiController::class);
Route::delete('/projects/{project}/datasets/{dataset}', DeleteDatasetApiController::class);
Route::put('/datasets/{dataset}/publish', PublishDatasetApiController::class);
Route::put('/datasets/{dataset}/unpublish', UnpublishDatasetApiController::class);
Route::put('/projects/{project}/datasets/{dataset}/change_file_selection',
    ChangeDatasetFileSelectionApiController::class);

Route::get('/projects/{project}/datasets/{dataset}/files/{file}/check_selection',
    FileInDatasetSelectionApiController::class);

Route::post('/projects/{project}/datasets/{dataset}/check_select_by_path',
    FileByPathInDatasetSelectionApiController::class);

Route::post('/projects/{p}/datasets/{dataset}/import', ImportDatasetIntoProjectApiController::class);



