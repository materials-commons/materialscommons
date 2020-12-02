<?php

use App\Http\Controllers\Web\Datasets\AssignDoiWebController;
use App\Http\Controllers\Web\Datasets\CreateDataDatasetActivitiesWebController;
use App\Http\Controllers\Web\Datasets\CreateDataDatasetSamplesWebController;
use App\Http\Controllers\Web\Datasets\CreateDataDatasetWebController;
use App\Http\Controllers\Web\Datasets\CreateDataDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Datasets\CreateDatasetWebController;
use App\Http\Controllers\Web\Datasets\CreateDatasetWorkflowFromEditWebController;
use App\Http\Controllers\Web\Datasets\CreateDataShowCreateDirectoryWebController;
use App\Http\Controllers\Web\Datasets\CreateDataShowUploadFilesWebController;
use App\Http\Controllers\Web\Datasets\CreateDataStoreCreateDirectoryWebController;
use App\Http\Controllers\Web\Datasets\DeleteDatasetWebController;
use App\Http\Controllers\Web\Datasets\DestroyDatasetWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetActivitiesWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetFilesWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetSamplesWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Datasets\EditDatasetWorkflowWebController;
use App\Http\Controllers\Web\Datasets\ImportDatasetIntoProjectWebController;
use App\Http\Controllers\Web\Datasets\IndexDatasetsWebController;
use App\Http\Controllers\Web\Datasets\PublishDatasetWebController;
use App\Http\Controllers\Web\Datasets\RefreshPublishedDatasetWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetActivitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetAndFolderWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetCommunitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetDataDictionaryWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetEntitiesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetExperimentsWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetFileIncludesExcludesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetFilesWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetOverviewWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetWebController;
use App\Http\Controllers\Web\Datasets\ShowDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Datasets\ShowImportDatasetIntoProjectWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWithDoiWebController;
use App\Http\Controllers\Web\Datasets\StoreDatasetWorkflowFromEditWebController;
use App\Http\Controllers\Web\Datasets\UnpublishDatasetWebController;
use App\Http\Controllers\Web\Datasets\UpdateDatasetWebController;
use App\Http\Controllers\Web\Datasets\UpdateDatasetWorkflowFromEditWebController;
use App\Http\Controllers\Web\Datasets\UploadFilesForDatasetWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/datasets', IndexDatasetsWebController::class)
     ->name('projects.datasets.index');

Route::get('/projects/{project}/datasets/create', CreateDatasetWebController::class)
     ->name('projects.datasets.create');
Route::post('/projects/{project}/datasets', StoreDatasetWebController::class)
     ->name('projects.datasets.store');

// edit dataset routes

Route::get('/projects/{project}/datasets/{dataset}/edit/samples', EditDatasetSamplesWebController::class)
     ->name('projects.datasets.samples.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/activities', EditDatasetActivitiesWebController::class)
     ->name('projects.datasets.activities.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/workflows', EditDatasetWorkflowsWebController::class)
     ->name('projects.datasets.workflows.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/files/{folder?}', EditDatasetFilesWebController::class)
     ->name('projects.datasets.files.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit', EditDatasetWebController::class)
     ->name('projects.datasets.edit');

Route::get('/projects/{project}/datasets/{dataset}/edit/workflows/create',
    CreateDatasetWorkflowFromEditWebController::class)
     ->name('projects.datasets.workflows.edit.create');

Route::post('/projects/{project}/datasets/{dataset}/edit/workflows/store',
    StoreDatasetWorkflowFromEditWebController::class)
     ->name('projects.datasets.workflows.edit.store');

Route::put('/projects/{project}/datasets/{dataset}/edit/workflows/{workflow}/update',
    UpdateDatasetWorkflowFromEditWebController::class)
     ->name('projects.datasets.workflows.edit.update');

Route::get('/projects/{project}/datasets/{dataset}/edit/workflows/{workflow}', EditDatasetWorkflowWebController::class)
     ->name('projects.datasets.workflows.edit.workflow');

// create-data routes

Route::get('/projects/{project}/datasets/{dataset}/create-data/samples', CreateDataDatasetSamplesWebController::class)
     ->name('projects.datasets.samples.create-data');

Route::get('/projects/{project}/datasets/{dataset}/create-data/activities',
    CreateDataDatasetActivitiesWebController::class)
     ->name('projects.datasets.activities.create-data');

Route::get('/projects/{project}/datasets/{dataset}/create-data/workflows',
    CreateDataDatasetWorkflowsWebController::class)
     ->name('projects.datasets.workflows.create-data');

Route::get('/projects/{project}/datasets/{dataset}/create-data/{folder?}', CreateDataDatasetWebController::class)
     ->name('projects.datasets.create-data');

Route::get('/projects/{project}/datasets/{dataset}/create-data/upload/{directory}',
    CreateDataShowUploadFilesWebController::class)
     ->name('projects.datasets.create-data.upload-files');

Route::post('/projects/{project}/datasets/{dataset}/files/{file}/upload', UploadFilesForDatasetWebController::class)
     ->name('projects.datasets.files.upload');


Route::get('/projects/{project}/datasets/{dataset}/create-data/create-directory/{directory}',
    CreateDataShowCreateDirectoryWebController::class)
     ->name('projects.datasets.create-data.create-directory');

Route::post('/projects/{project}/datasets/{dataset}/store-directory',
    CreateDataStoreCreateDirectoryWebController::class)
     ->name('projects.datasets.create-data.store-directory');

Route::get('/projects/{project}/datasets/{dataset}/overview', ShowDatasetOverviewWebController::class)
     ->name('projects.datasets.show.overview');

Route::get('/projects/{project}/datasets/{dataset}', ShowDatasetWebController::class)
     ->name('projects.datasets.show');

Route::get('/projects/{project}/datasets/{dataset}/files', ShowDatasetFilesWebController::class)
     ->name('projects.datasets.show.files');
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

Route::get('/projects/{project}/datasets/{dataset}/file_includes_excludes',
    ShowDatasetFileIncludesExcludesWebController::class)
     ->name('projects.datasets.show.file_includes_excludes');

Route::get('/projects/{project}/datasets/{dataset}/data-dictionary', ShowDatasetDataDictionaryWebController::class)
     ->name('projects.datasets.show.data-dictionary');

Route::patch('/projects/{project}/datasets/{dataset}', UpdateDatasetWebController::class)
     ->name('projects.datasets.update');
Route::put('/projects/{project}/datasets/{dataset}', UpdateDatasetWebController::class)
     ->name('projects.datasets.update');


Route::get('/projects/{project}/datasets/{dataset}/delete', DeleteDatasetWebController::class)
     ->name('projects.datasets.delete');

Route::delete('/projects/{project}/datasets/{dataset}', DestroyDatasetWebController::class)
     ->name('projects.datasets.destroy');

Route::put('/projects/{project}/datasets/{dataset}/assign-doi', AssignDoiWebController::class)
     ->name('projects.datasets.assign-doi');

Route::post('/projects/{project}/datasets/create-doi', StoreDatasetWithDoiWebController::class)
     ->name('projects.datasets.create-doi');

Route::get('/projects/{project}/datasets/{dataset}/publish', PublishDatasetWebController::class)
     ->name('projects.datasets.publish');

Route::get('/projects/{project}/datasets/{dataset}/refresh', RefreshPublishedDatasetWebController::class)
     ->name('projects.datasets.refresh');

Route::get('/projects/{project}/datasets/{dataset}/unpublish', UnpublishDatasetWebController::class)
     ->name('projects.datasets.unpublish');

// Don't use {project} because we don't want middleware to run that checks that dataset is in project
Route::post('/projects/{p}/datasets/{dataset}/import', ImportDatasetIntoProjectWebController::class)
     ->name('projects.datasets.import');

Route::get('/projects/{p}/datasets/{dataset}/import', ShowImportDatasetIntoProjectWebController::class)
     ->name('projects.datasets.import-into-project');



