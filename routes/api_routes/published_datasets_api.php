<?php

// Published datasets

use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetActivitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetEntitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetFilesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetsApiController;
use App\Http\Controllers\Api\Datasets\ShowPublishedDatasetApiController;
use Illuminate\Support\Facades\Route;

Route::get('/published/datasets', IndexPublishedDatasetsApiController::class);
Route::get('/published/datasets/{dataset}', ShowPublishedDatasetApiController::class);
Route::get('/published/datasets/{dataset}/files', IndexPublishedDatasetFilesApiController::class);
Route::get('/published/datasets/{dataset}/entities', IndexPublishedDatasetEntitiesApiController::class);
Route::get('/published/datasets/{dataset}/activities', IndexPublishedDatasetActivitiesApiController::class);
