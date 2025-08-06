<?php

use App\Http\Controllers\Web\Projects\CreateProjectWebController;
use App\Http\Controllers\Web\Projects\Datatables\GetRecentlyUploadedFilesWebController;
use App\Http\Controllers\Web\Projects\DeleteProjectWebController;
use App\Http\Controllers\Web\Projects\EditProjectWebController;
use App\Http\Controllers\Web\Projects\Globus\CloseOpenGlobusTransfersWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\CreateProjectGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\DeleteGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\DestroyGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\EditGlobusAccountWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\IndexProjectGlobusDownloadsWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\ShowProjectGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\StoreGlobusDownloadProjectWebController;
use App\Http\Controllers\Web\Projects\Globus\Downloads\UpdateGlobusAccountWebController;
use App\Http\Controllers\Web\Projects\Globus\NG2\CloseOpenGlobus2TransfersWebController;
use App\Http\Controllers\Web\Projects\Globus\NG2\StartGlobus2TransferWebController;
use App\Http\Controllers\Web\Projects\Globus\RedirectToProjectGlobusSiteWebController;
use App\Http\Controllers\Web\Projects\Globus\ShowStartedGlobusTransferWebController;
use App\Http\Controllers\Web\Projects\Globus\StartGlobusTransferWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\CreateProjectGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\DeleteGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\DestroyGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\EditGlobusAccountForUploadsWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\IndexProjectGlobusUploadsWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\MarkGlobusUploadAsCompleteWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\ShowGlobusUploadToMarkAsCompleteWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\ShowProjectGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\StoreGlobusUploadToProjectWebController;
use App\Http\Controllers\Web\Projects\Globus\Uploads\UpdateGlobusAccountForUploadsWebController;
use App\Http\Controllers\Web\Projects\IndexProjectsWebController;
use App\Http\Controllers\Web\Projects\MarkProjectAsActiveWebController;
use App\Http\Controllers\Web\Projects\SearchAcrossProjectsWebController;
use App\Http\Controllers\Web\Projects\SearchProjectWebController;
use App\Http\Controllers\Web\Projects\ShowProjectActivitiesDataDictionaryWebController;
use App\Http\Controllers\Web\Projects\ShowProjectEntitiesDataDictionaryWebController;
use App\Http\Controllers\Web\Projects\ShowProjectOverviewWebController;
use App\Http\Controllers\Web\Projects\ShowProjectWebController;
use App\Http\Controllers\Web\Projects\ShowUploadFilesWebController;
use App\Http\Controllers\Web\Projects\StoreProjectWebController;
use App\Http\Controllers\Web\Projects\UnmarkProjectAsActiveWebController;
use App\Http\Controllers\Web\Projects\UpdateProjectWebController;
use App\Http\Controllers\Web\Projects\Users\AddAdminToProjectWebController;
use App\Http\Controllers\Web\Projects\Users\AddUserToProjectWebController;
use App\Http\Controllers\Web\Projects\Users\ChangeUserToAdminWebController;
use App\Http\Controllers\Web\Projects\Users\ChangeUserToMemberWebController;
use App\Http\Controllers\Web\Projects\Users\IndexProjectUsersWebController;
use App\Http\Controllers\Web\Projects\Users\ModifyProjectUsersWebController;
use App\Http\Controllers\Web\Projects\Users\RemoveAdminFromProjectWebController;
use App\Http\Controllers\Web\Projects\Users\RemoveUserFromProjectWebController;
use App\Http\Controllers\Web\Projects\Users\ShowProjectUserWebController;
use App\Http\Controllers\Web\Visus\ShowVisusDatasetWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/search-all', SearchAcrossProjectsWebController::class)
     ->name('projects.search_all');

Route::get('/projects/{project}/mark-as-active', MarkProjectAsActiveWebController::class)
     ->name('projects.mark-as-active');

Route::get('/projects/{project}/unmark-as-active', UnmarkProjectAsActiveWebController::class)
     ->name('projects.unmark-as-active');

Route::get('/projects/create', CreateProjectWebController::class)
     ->name('projects.create');
Route::post('/projects', StoreProjectWebController::class)
     ->name('projects.store');

Route::get('/projects', IndexProjectsWebController::class)
     ->name('projects.index');

Route::get('/projects/{project}/upload', ShowUploadFilesWebController::class)
     ->name('projects.upload-files');

Route::get('/projects/{project}', ShowProjectWebController::class)
     ->name('projects.show');

Route::get('/projects/{project}/overview', ShowProjectOverviewWebController::class)
     ->name('projects.overview');

//

Route::patch('/projects/{project}', UpdateProjectWebController::class)
     ->name('projects.update');
Route::get('/projects/{project}/edit', EditProjectWebController::class)
     ->name('projects.edit');

Route::delete('/projects/{project}', DeleteProjectWebController::class)
     ->name('projects.destroy');

Route::get('/projects/{project}/users/{user}/remove', RemoveUserFromProjectWebController::class)
     ->name('projects.users.remove');

Route::get('/projects/{project}/users/{user}/add', AddUserToProjectWebController::class)
     ->name('projects.users.add');

Route::get('/projects/{project}/admins/{user}/remove', RemoveAdminFromProjectWebController::class)
     ->name('projects.admins.remove');

Route::get('/projects/{project}/admins/{user}/add', AddAdminToProjectWebController::class)
     ->name('projects.admins.add');

Route::get('/projects/{project}/users', IndexProjectUsersWebController::class)
     ->name('projects.users.index');

Route::get('/projects/{project}/users/edit', ModifyProjectUsersWebController::class)
     ->name('projects.users.edit');

Route::get('/projects/{project}/users/{user}/show', ShowProjectUserWebController::class)
     ->name('projects.users.show');

Route::get('/projects/{project}/users/{user}/change-to-admin', ChangeUserToAdminWebController::class)
     ->name('projects.users.change-to-admin');

Route::get('/projects/{project}/users/{user}/change-to-member', ChangeUserToMemberWebController::class)
     ->name('projects.users.change-to-member');


// Globus Uploads

Route::post('/projects/{project}/globus/uploads', StoreGlobusUploadToProjectWebController::class)
     ->name('projects.globus.uploads.store');

Route::get('/projects/{project}/globus/uploads/create', CreateProjectGlobusUploadWebController::class)
     ->name('projects.globus.uploads.create');

Route::get('/projects/{project}/globus/uploads/account/edit', EditGlobusAccountForUploadsWebController::class)
     ->name('projects.globus.uploads.edit_account');

Route::post('/projects/{project}/globus/uploads/account/update', UpdateGlobusAccountForUploadsWebController::class)
     ->name('projects.globus.uploads.update_account');

Route::get('/projects/{project}/globus/uploads/{globusUpload}/delete', DeleteGlobusUploadWebController::class)
     ->name('projects.globus.uploads.delete');

Route::delete('/projects/{project}/globus/uploads/{globusUpload}', DestroyGlobusUploadWebController::class)
     ->name('projects.globus.uploads.destroy');

Route::get('/projects/{project}/globus/uploads/{globusUpload}/done',
    ShowGlobusUploadToMarkAsCompleteWebController::class)
     ->name('projects.globus.uploads.done');

Route::post('/projects/{project}/globus/uploads/{globusUpload}/mark_done',
    MarkGlobusUploadAsCompleteWebController::class)
     ->name('projects.globus.uploads.mark_done');

Route::get('/projects/{project}/globus/uploads/index', IndexProjectGlobusUploadsWebController::class)
     ->name('projects.globus.uploads.index');

Route::get('/projects/{project}/globus/uploads/{globusUpload}', ShowProjectGlobusUploadWebController::class)
     ->name('projects.globus.uploads.show');


// Globus Downloads

Route::post('/projects/{project}/globus/downloads', StoreGlobusDownloadProjectWebController::class)
     ->name('projects.globus.downloads.store');

Route::get('/projects/{project}/globus/downloads/create', CreateProjectGlobusDownloadWebController::class)
     ->name('projects.globus.downloads.create');

Route::get('/projects/{project}/globus/downloads/account/edit', EditGlobusAccountWebController::class)
     ->name('projects.globus.downloads.edit_account');

Route::post('/projects/{project}/globus/downloads/account/update', UpdateGlobusAccountWebController::class)
     ->name('projects.globus.downloads.update_account');

Route::get('/projects/{project}/globus/downloads/{globusDownload}/delete', DeleteGlobusDownloadWebController::class)
     ->name('projects.globus.downloads.delete');

Route::delete('/projects/{project}/globus/downloads/{globusDownload}', DestroyGlobusDownloadWebController::class)
     ->name('projects.globus.downloads.destroy');

Route::get('/projects/{project}/globus/downloads/index', IndexProjectGlobusDownloadsWebController::class)
     ->name('projects.globus.downloads.index');

Route::get('/projects/{project}/globus/downloads/{globusDownload}', ShowProjectGlobusDownloadWebController::class)
     ->name('projects.globus.downloads.show');

// Globus NG
Route::get('/projects/{project}/globus/start', StartGlobusTransferWebController::class)
     ->name('projects.globus.start');

Route::get('/projects/{project}/globus/close', CloseOpenGlobusTransfersWebController::class)
     ->name('projects.globus.close');

Route::get('/projects/{project}/globus/{globusTransfer}/show-started', ShowStartedGlobusTransferWebController::class)
     ->name('projects.globus.show-started');

Route::get('/projects/{project}/data-dictionary/activities', ShowProjectActivitiesDataDictionaryWebController::class)
     ->name('projects.data-dictionary.activities');

Route::get('/projects/{project}/data-dictionary/entities', ShowProjectEntitiesDataDictionaryWebController::class)
     ->name('projects.data-dictionary.entities');

// Globus NG2
Route::get('/projects/{project}/globus2/start', StartGlobus2TransferWebController::class)
     ->name('projects.globus2.start');

Route::get('/projects/{project}/globus2/close', CloseOpenGlobus2TransfersWebController::class)
     ->name('projects.globus2.close');

Route::get('/projects/{project}/globus2/{globusTransfer}/show-started', ShowStartedGlobusTransferWebController::class)
     ->name('projects.globus2.show-started');

//Route::get('/projects/{project}/globus/monitor', MonitorGlobusTransferWebController::class)
//     ->name('projects.globus.monitor');

//Route::get('/projects/{project}/globus/{globusRequest}/file-upload-status',
//    DTGetGlobusRequestUploadedFilesWebController::class)
//     ->name('projects.globus.dt-file-upload-status');

Route::get('/projects/{project}/dt/recently-uploaded', GetRecentlyUploadedFilesWebController::class)
     ->name('projects.dt.recently-uploaded');

// Project Search

Route::post('/projects/{project}/search', SearchProjectWebController::class)
     ->name('projects.search');

Route::get('/projects/{project}/search/htmx', SearchProjectWebController::class)
    ->name('projects.search.htmx');

// Project Globus Bookmark
Route::get("/projects/{project}/globus_bookmark", RedirectToProjectGlobusSiteWebController::class)
     ->name('projects.globus.bookmark');

// For now Visus datasets here
Route::get('/projects/{project}/files/{file}/visus', ShowVisusDatasetWebController::class)
     ->name('projects.files.show-visus');

// Markdown Editor
Route::get('/projects/{project}/markdown-editor', 'App\Http\Controllers\Web\Projects\ShowMarkdownEditorWebController')
     ->name('projects.markdown-editor');
