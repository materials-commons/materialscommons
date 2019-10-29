<?php

use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetActivitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetEntitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetActivitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetEntitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetWebController;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}', ShowPublishedDatasetWebController::class)->name('datasets.show');

Route::get('/datasets/{dataset}/entities', ShowPublishedDatasetEntitiesWebController::class)->name('datasets.entities.index');

Route::get('/datasets/{dataset}/activities', ShowPublishedDatasetActivitiesWebController::class)->name('datasets.activities.index');

Route::get('/datasets/{dataset}/datatables/activities', GetDatasetActivitiesDatatableWebController::class)
     ->name('dt_get_dataset_activities');
Route::get('/datasets/{dataset}/datatables/entities', GetDatasetEntitiesDatatableWebController::class)
     ->name('dt_get_dataset_entities');


