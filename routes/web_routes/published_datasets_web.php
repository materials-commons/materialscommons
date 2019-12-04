<?php

use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetActivitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetEntitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetFilesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetActivitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetCommentsWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetEntitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetFilesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetWebController;
use App\Http\Controllers\Web\Published\Files\ShowPublishedFileWebController;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}', ShowPublishedDatasetWebController::class)
     ->name('datasets.show');

Route::get('/datasets/{dataset}/entities', ShowPublishedDatasetEntitiesWebController::class)
     ->name('datasets.entities.index');

Route::get('/datasets/{dataset}/activities', ShowPublishedDatasetActivitiesWebController::class)
     ->name('datasets.activities.index');

Route::get('/datasets/{dataset}/files', ShowPublishedDatasetFilesWebController::class)
     ->name('datasets.files.index');

Route::get('/datasets/{dataset}/comments', ShowPublishedDatasetCommentsWebController::class)
     ->name('datasets.comments.index');

// Published file
ROute::get('/datasets/{dataset}/files/{file}', ShowPublishedFileWebController::class)
     ->name('datasets.files.show');


// Datatables
Route::get('/datasets/{dataset}/datatables/activities', GetDatasetActivitiesDatatableWebController::class)
     ->name('dt_get_dataset_activities');

Route::get('/datasets/{dataset}/datatables/entities', GetDatasetEntitiesDatatableWebController::class)
     ->name('dt_get_dataset_entities');

Route::get('/datasets/{dataset}/datatables/files', GetDatasetFilesDatatableWebController::class)
     ->name('dt_get_published_dataset_files');