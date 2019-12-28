<?php

use App\Http\Controllers\Web\Datasets\AssignDoiWebController;
use App\Http\Controllers\Web\Datasets\CreateDatasetWebController;
use App\Http\Controllers\Web\Datasets\DeleteDatasetWebController;
use App\Http\Controllers\Web\Datasets\DestroyDatasetWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetActivitiesWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetSamplesWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Datasets\IndexDatasetsWebController;
use App\Http\Controllers\Web\Datasets\PublishDatasetWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetActivitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetAndFolderWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetCommunitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetEntitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetExperimentsWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWithDoiWebController;
use App\Http\Controllers\Web\Datasets\UnpublishDatasetWebController;
use App\Http\Controllers\Web\Datasets\UpdateDatasetWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/datasets', IndexDatasetsWebController::class)
     ->name('projects.datasets.index');

Route::get('/projects/{project}/datasets/create', CreateDatasetWebController::class)
     ->name('projects.datasets.create');
Route::post('/projects/{project}/datasets', StoreDatasetWebController::class)
     ->name('projects.datasets.store');

Route::get('/projects/{project}/datasets/{dataset}/edit/samples', EditDatasetSamplesWebController::class)
     ->name('projects.datasets.samples.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/activities', EditDatasetActivitiesWebController::class)
     ->name('projects.datasets.activities.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/workflows', EditDatasetWorkflowsWebController::class)
     ->name('projects.datasets.workflows.edit');

Route::get('/projects/{project}/datasets/{dataset}', ShowDatasetWebController::class)
     ->name('projects.datasets.show');
Route::get('/projects/{project}/datasets/{dataset}/next/{folder}', ShowDatasetAndFolderWebController::class)
     ->name('projects.datasets.show.next');

Route::get('/projects/{project}/datasets/{dataset}/entities', ShowDatasetEntitiesWebController::class)
     ->name('projects.datasets.show.entities');

Route::get('/projects/{project}/datasets/{dataset}/activities', ShowDatasetActivitiesWebController::class)
     ->name('projects.datasets.show.activities');

Route::get('/projects/{project}/datasets/{dataset}/workflows', ShowDatasetWorkflowsWebController::class)
     ->name('projects.datasets.show.workflows');

Route::get('/projects/{project}/datasets/{dataset}/communities', ShowDatasetCommunitiesWebController::class)
     ->name('projects.datasets.show.communities');

Route::get('/projects/{project}/datasets/{dataset}/experiments', ShowDatasetExperimentsWebController::class)
     ->name('projects.datasets.show.experiments');

Route::patch('/projects/{project}/datasets/{dataset}', UpdateDatasetWebController::class)
     ->name('projects.datasets.update');
Route::put('/projects/{project}/datasets/{dataset}', UpdateDatasetWebController::class)
     ->name('projects.datasets.update');
Route::get('/projects/{project}/datasets/{dataset}/edit/{folder?}', EditDatasetWebController::class)
     ->name('projects.datasets.edit');

Route::get('/projects/{project}/datasets/{dataset}/delete', DeleteDatasetWebController::class)
     ->name('projects.datasets.delete');

Route::delete('/projects/{project}/datasets/{dataset}', DestroyDatasetWebController::class)
     ->name('projects.datasets.destroy');

Route::get('/projects/{project}/datasets/{dataset}/assign-doi', AssignDoiWebController::class)
     ->name('projects.datasets.assign-doi');

Route::post('/projects/{project}/datasets/create-doi', StoreDatasetWithDoiWebController::class)
     ->name('projects.datasets.create-doi');

Route::get('/projects/{project}/datasets/{dataset}/publish', PublishDatasetWebController::class)
     ->name('projects.datasets.publish');

Route::get('/projects/{project}/datasets/{dataset}/unpublish', UnpublishDatasetWebController::class)
     ->name('projects.datasets.unpublish');



