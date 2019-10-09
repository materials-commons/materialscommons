<?php

use App\Http\Controllers\Web\Datasets\CreateDatasetWebController;
use App\Http\Controllers\Web\Datasets\DeleteDatasetWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWebController;
use App\Http\Controllers\Web\Datasets\Files\EditDatasetFilesWebController;
use App\Http\Controllers\Web\Datasets\Files\ShowDatasetFilesDirectoryWebController;
use App\Http\Controllers\Web\Datasets\IndexDatasetsWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWebController;
use App\Http\Controllers\Web\Datasets\UpdateDatasetWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/datasets', IndexDatasetsWebController::class)
     ->name('projects.datasets.index');

Route::get('/projects/{project}/datasets/create', CreateDatasetWebController::class)
     ->name('projects.datasets.create');
Route::post('/projects/{project}/datasets', StoreDatasetWebController::class)
     ->name('projects.datasets.store');

Route::get('/projects/{project}/datasets/{dataset}/files', EditDatasetFilesWebController::class)
     ->name('projects.datasets.files.edit');
Route::get('/projects/{project}/datasets/{dataset}/folders/{folder}', ShowDatasetFilesDirectoryWebController::class)
     ->name('projects.datasets.folders.show');

Route::get('/projects/{project}/datasets/{dataset}', ShowDatasetWebController::class)
     ->name('projects.datasets.show');

Route::patch('/projects/{project}/datasets/{dataset}', UpdateDatasetWebController::class)
     ->name('projects.datasets.update');
Route::get('/projects/{project}//datasets/{dataset}/edit', EditDatasetWebController::class)
     ->name('projects.datasets.edit');

Route::delete('/projects/{project}/datasets/{dataset}', DeleteDatasetWebController::class)
     ->name('projects.datasets.destroy');


