<?php

use App\Http\Controllers\Published\Datasets\SearchPublishedDatasetWebController;
use App\Http\Controllers\Web\Published\Datasets\Activities\ShowPublishedDatasetActivityWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\CreateDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\DeleteDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\DestroyDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\EditDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\StoreDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Comments\UpdateDatasetCommentWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetActivitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetEntitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetFilesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\DownloadDatasetFileWebController;
use App\Http\Controllers\Web\Published\Datasets\DownloadDatasetGlobusRedirectWebController;
use App\Http\Controllers\Web\Published\Datasets\DownloadDatasetZipfileWebController;
use App\Http\Controllers\Web\Published\Datasets\Entities\ShowPublishedDatasetEntitySpreadWebController;
use App\Http\Controllers\Web\Published\Datasets\Entities\ShowPublishedDatasetEntityWebController;
use App\Http\Controllers\Web\Published\Datasets\Login\CreateAccountForPublishedDatasetGlobusDownloadWebController;
use App\Http\Controllers\Web\Published\Datasets\Login\CreateAccountForPublishedDatasetZipfileDownloadWebController;
use App\Http\Controllers\Web\Published\Datasets\Login\LoginForPublishedDatasetGlobusDownloadWebController;
use App\Http\Controllers\Web\Published\Datasets\Login\LoginForPublishedDatasetZipfileDownloadWebController;
use App\Http\Controllers\Web\Published\Datasets\MarkDatasetForNotificationsWebController;
use App\Http\Controllers\Web\Published\Datasets\Folders\GotoPublishedDatasetFolderByPathWebController;
use App\Http\Controllers\Web\Published\Datasets\Folders\ShowPublishedDatasetFolderWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowImportPublishedDatasetIntoProjectWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetActivitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetCommentsWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetCommunitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetEntitiesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetFilesWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetOverviewWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetWebController;
use App\Http\Controllers\Web\Published\Datasets\ShowPublishedDatasetWorkflowsWebController;
use App\Http\Controllers\Web\Published\Datasets\UnmarkDatasetForNotificationsWebController;
use App\Http\Controllers\Web\Published\Files\DisplayPublishedFileWebController;
use App\Http\Controllers\Web\Published\Files\ShowPublishedFileWebController;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}', ShowPublishedDatasetWebController::class)
     ->name('public.datasets.show');

Route::get('/datasets/{dataset}/search', SearchPublishedDatasetWebController::class)
     ->name('public.datasets.search');

Route::get('/datasets/{dataset}/overview', ShowPublishedDatasetOverviewWebController::class)
     ->name('public.datasets.overview.show');

Route::get('/datasets/{dataset}/workflows', ShowPublishedDatasetWorkflowsWebController::class)
     ->name('public.datasets.workflows.index');

Route::get('/datasets/{dataset}/zipfile', DownloadDatasetZipfileWebController::class)
     ->name('public.datasets.download_zipfile');

Route::get('/datasets/{dataset}/login/download/zipfile', LoginForPublishedDatasetZipfileDownloadWebController::class)
     ->name('public.datasets.login.download.zipfile');

Route::get('/datasets/{dataset}/create-account/download/zipfile',
    CreateAccountForPublishedDatasetZipfileDownloadWebController::class)
     ->name('public.datasets.create-account.download.zipfile');

//Route::get('/datasets/{dataset}/files/{file}/download', DownloadDatasetFileWebController::class)
//     ->name('public.datasets.download_file');

Route::get('/datasets/{dataset}/globus', DownloadDatasetGlobusRedirectWebController::class)
     ->name('public.datasets.download_globus');

Route::get('/datasets/{dataset}/login/download/globus', LoginForPublishedDatasetGlobusDownloadWebController::class)
     ->name('public.datasets.login.download.globus');

Route::get('/datasets/{dataset}/create-account/download/globus',
    CreateAccountForPublishedDatasetGlobusDownloadWebController::class)
     ->name('public.datasets.create-account.download.globus');

Route::get('/datasets/{dataset}/entities', ShowPublishedDatasetEntitiesWebController::class)
     ->name('public.datasets.entities.index');

Route::get('/datasets/{dataset}/activities', ShowPublishedDatasetActivitiesWebController::class)
     ->name('public.datasets.activities.index');

Route::get('/datasets/{dataset}/files', ShowPublishedDatasetFilesWebController::class)
     ->name('public.datasets.files.index');

Route::get('/datasets/{dataset}/comments', ShowPublishedDatasetCommentsWebController::class)
     ->name('public.datasets.comments.index');

Route::get('/datasets/{dataset}/communities', ShowPublishedDatasetCommunitiesWebController::class)
     ->name('public.datasets.communities.index');

// Don't use {project} because we don't want the middleware to run that checks that the dataset is in the project
Route::get('/datasets/{dataset}/import-into/projects/{p}',
    ShowImportPublishedDatasetIntoProjectWebController::class)
     ->name('public.datasets.import-into-project');

// Entity

Route::get('/datasets/{dataset}/entities/{entity}/spread', ShowPublishedDatasetEntitySpreadWebController::class)
     ->name('public.datasets.entities.show-spread');

Route::get('/datasets/{dataset}/entities/{entity}', ShowPublishedDatasetEntityWebController::class)
     ->name('public.datasets.entities.show');

// Activity
Route::get('/datasets/{dataset}/activities/{activity}', ShowPublishedDatasetActivityWebController::class)
     ->name('public.datasets.activities.show');

// Published file
Route::get('/datasets/{dataset}/files/{file}', ShowPublishedFileWebController::class)
     ->name('public.datasets.files.show');

Route::get('/datasets/{dataset}/files/{file}/display', DisplayPublishedFileWebController::class)
     ->name('public.datasets.files.display');

// Datatables
Route::get('/datasets/{dataset}/datatables/activities', GetDatasetActivitiesDatatableWebController::class)
     ->name('public.dt_get_dataset_activities');

Route::get('/datasets/{dataset}/datatables/entities', GetDatasetEntitiesDatatableWebController::class)
     ->name('public.dt_get_dataset_entities');

Route::get('/datasets/{dataset}/datatables/files', GetDatasetFilesDatatableWebController::class)
     ->name('public.dt_get_published_dataset_files');

// Comments
Route::get('/datasets/{dataset}/comments/create', CreateDatasetCommentWebController::class)
     ->name('public.datasets.comments.create');

Route::get('/datasets/{dataset}/comments/{comment}/edit', EditDatasetCommentWebController::class)
     ->name('public.datasets.comments.edit');

Route::post('/datasets/{dataset}/comments/store', StoreDatasetCommentWebController::class)
     ->name('public.datasets.comments.store');

Route::put('/datasets/{dataset}/comments/{comment}', UpdateDatasetCommentWebController::class)
     ->name('public.datasets.comments.update');

Route::get('/datasets/{dataset}/comments/{comment}/delete', DeleteDatasetCommentWebController::class)
     ->name('public.datasets.comments.delete');

Route::delete('/datasets/{dataset}/comments/{comment}', DestroyDatasetCommentWebController::class)
     ->name('public.datasets.comments.destroy');

// Notifications
Route::get('/datasets/{dataset}/mark-for-notification', MarkDatasetForNotificationsWebController::class)
     ->name('public.datasets.notifications.mark-for-notification');
Route::get('/dataset/{dataset}/unmark-for-notification', UnmarkDatasetForNotificationsWebController::class)
     ->name('public.datasets.notifications.unmark-for-notification');

// Folders
Route::get('/datasets/{dataset}/folders/by_path', GotoPublishedDatasetFolderByPathWebController::class)
     ->name('public.datasets.folders.by-path');
Route::get('/datasets/{dataset}/folders/{folder}/show', ShowPublishedDatasetFolderWebController::class)
     ->name('public.datasets.folders.show');
