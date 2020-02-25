<?php

use App\Http\Controllers\Web\Published\Datasets\Comments\CreateDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\DeleteDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\DestroyDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\EditDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\StoreDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\UpdateDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetActivitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetEntitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetFilesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\DownloadDatasetGlobusRedirectWebController;
use App\Http\Controllers\Web\Published\Datasets\DownloadDatasetZipfileWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetActivitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetCommentsWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetCommunitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetEntitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetFilesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetWebController;
use App\Http\Controllers\Web\Published\Files\DisplayPublishedFileWebController;
use App\Http\Controllers\Web\Published\Files\ShowPublishedFileWebController;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}', ShowPublishedDatasetWebController::class)
     ->name('datasets.show');

Route::get('/datasets/{dataset}/zipfile', DownloadDatasetZipfileWebController::class)
     ->name('datasets.download_zipfile');

Route::get('/datasets/{dataset}/globus', DownloadDatasetGlobusRedirectWebController::class)
     ->name('datasets.download_globus');

Route::get('/datasets/{dataset}/entities', ShowPublishedDatasetEntitiesWebController::class)
     ->name('datasets.entities.index');

Route::get('/datasets/{dataset}/activities', ShowPublishedDatasetActivitiesWebController::class)
     ->name('datasets.activities.index');

Route::get('/datasets/{dataset}/files', ShowPublishedDatasetFilesWebController::class)
     ->name('datasets.files.index');

Route::get('/datasets/{dataset}/comments', ShowPublishedDatasetCommentsWebController::class)
     ->name('datasets.comments.index');

Route::get('/datasets/{dataset}/communities', ShowPublishedDatasetCommunitiesWebController::class)
     ->name('datasets.communities.index');

// Published file
Route::get('/datasets/{dataset}/files/{file}', ShowPublishedFileWebController::class)
     ->name('datasets.files.show');

Route::get('/datasets/{dataset}/files/{file}/display', DisplayPublishedFileWebController::class)
     ->name('datasets.files.display');


// Datatables
Route::get('/datasets/{dataset}/datatables/activities', GetDatasetActivitiesDatatableWebController::class)
     ->name('dt_get_dataset_activities');

Route::get('/datasets/{dataset}/datatables/entities', GetDatasetEntitiesDatatableWebController::class)
     ->name('dt_get_dataset_entities');

Route::get('/datasets/{dataset}/datatables/files', GetDatasetFilesDatatableWebController::class)
     ->name('dt_get_published_dataset_files');

// Comments
Route::get('/datasets/{dataset}/comments/create', CreateDatasetCommentWebController::class)
     ->name('datasets.comments.create');
Route::get('/datasets/{dataset}/comments/{comment}/edit', EditDatasetCommentWebController::class)
     ->name('datasets.comments.edit');
Route::post('/datasets/{dataset}/comments/store', StoreDatasetCommentWebController::class)
     ->name('datasets.comments.store');
Route::put('/datasets/{dataset}/comments/{comment}', UpdateDatasetCommentWebController::class)
     ->name('datasets.comments.update');
Route::get('/datasets/{dataset}/comments/{comment}/delete', DeleteDatasetCommentWebController::class)
     ->name('datasets.comments.delete');
Route::delete('/datasets/{dataset}/comments/{comment}', DestroyDatasetCommentWebController::class)
     ->name('datasets.comments.destroy');