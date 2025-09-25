<?php

// Published datasets

use App\Http\Controllers\Api\Datasets\Directories\IndexPublishedDatasetDirectoryApiController;
use App\Http\Controllers\Api\Datasets\Directories\IndexPublishedDatasetDirectoryByPathApiController;
use App\Http\Controllers\Api\Datasets\Directories\ShowPublishedDatasetDirectoryApiController;
use App\Http\Controllers\Api\Datasets\DownloadPublishedDatasetFileApiController;
use App\Http\Controllers\Api\Datasets\DownloadPublishedDatasetZipfileApiController;
use App\Http\Controllers\Api\Datasets\IndexAllPublishedDatasetsForFilesMatchingApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetActivitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetEntitiesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetFilesApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetForFilesMatchingApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetsApiController;
use App\Http\Controllers\Api\Datasets\IndexPublishedDatasetsByNameApiController;
use App\Http\Controllers\Api\Datasets\ShowPublishedDatasetApiController;
use App\Http\Controllers\Api\Datasets\ShowPublishedDatasetByDOIApiController;
use Illuminate\Support\Facades\Route;

// Get published dataset(s)
Route::get('/published/datasets', IndexPublishedDatasetsApiController::class);
Route::post("/published/datasets/by-name", IndexPublishedDatasetsByNameApiController::class);
Route::post("/published/datasets/by-doi", ShowPublishedDatasetByDOIApiController::class);
Route::get('/published/datasets/{dataset}', ShowPublishedDatasetApiController::class);
Route::get('/published/datasets/{dataset}/files', IndexPublishedDatasetFilesApiController::class);
Route::get('/published/datasets/{dataset}/entities', IndexPublishedDatasetEntitiesApiController::class);
Route::get('/published/datasets/{dataset}/activities', IndexPublishedDatasetActivitiesApiController::class);
Route::get('/published/datasets/{dataset}/files/{file}/download', DownloadPublishedDatasetFileApiController::class);
Route::get('/published/datasets/{dataset}/download_zipfile', DownloadPublishedDatasetZipfileApiController::class);

// Published dataset directories
Route::get('/published/datasets/{dataset}/directories/{directory}', ShowPublishedDatasetDirectoryApiController::class);
Route::get('/published/datasets/{dataset}/directories/{directory}/list',
    IndexPublishedDatasetDirectoryApiController::class);
Route::get('/published/datasets/{dataset}/directories_by_path',
    IndexPublishedDatasetDirectoryByPathApiController::class);

// Find file matches
Route::post("/published/datasets/files/matching", IndexAllPublishedDatasetsForFilesMatchingApiController::class);
Route::post("/published/datasets/{dataset}/files/matching", IndexPublishedDatasetForFilesMatchingApiController::class);

