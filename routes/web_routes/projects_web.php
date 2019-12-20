<?php

use App\Http\Controllers\Web\Projects\CreateProjectWebController;
use App\Http\Controllers\Web\Projects\DeleteProjectWebController;
use App\Http\Controllers\Web\Projects\EditProjectWebController;
use App\Http\Controllers\Web\Projects\Globus\CreateProjectGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\DeleteGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\DestroyGlobusDownloadWebController;
use App\Http\Controllers\Web\Projects\Globus\GlobusGetProjectDownloadLinkWebController;
use App\Http\Controllers\Web\Projects\Globus\MarkGlobusDownloadAsCompleteWebController;
use App\Http\Controllers\Web\Projects\Globus\ShowGlobusDownloadToMarkAsCompleteWebController;
use App\Http\Controllers\Web\Projects\Globus\ShowProjectGlobusUploadsStatusWebController;
use App\Http\Controllers\Web\Projects\Globus\ShowProjectGlobusUploadWebController;
use App\Http\Controllers\Web\Projects\Globus\StoreGlobusUploadToProjectWebController;
use App\Http\Controllers\Web\Projects\IndexProjectsWebController;
use App\Http\Controllers\Web\Projects\SearchProjectWebController;
use App\Http\Controllers\Web\Projects\ShowProjectWebController;
use App\Http\Controllers\Web\Projects\StoreProjectWebController;
use App\Http\Controllers\Web\Projects\UpdateProjectWebController;
use App\Http\Controllers\Web\Projects\Users\AddUserToProjectWebController;
use App\Http\Controllers\Web\Projects\Users\IndexProjectUsersWebController;
use App\Http\Controllers\Web\Projects\Users\ModifyProjectUsersWebController;
use App\Http\Controllers\Web\Projects\Users\RemoveUserFromProjectWebController;
use App\Http\Controllers\Web\Projects\Users\ShowProjectUserWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/create', CreateProjectWebController::class)->name('projects.create');
Route::post('/projects', StoreProjectWebController::class)->name('projects.store');

Route::get('/projects', IndexProjectsWebController::class)->name('projects.index');
Route::get('/projects/{project}', ShowProjectWebController::class)->name('projects.show');

Route::patch('/projects/{project}', UpdateProjectWebController::class)->name('projects.update');
Route::get('/projects/{project}/edit', EditProjectWebController::class)->name('projects.edit');

Route::delete('/projects/{project}', DeleteProjectWebController::class)->name('projects.destroy');

Route::get('/projects/{project}/users/{user}/remove', RemoveUserFromProjectWebController::class)
     ->name('projects.users.remove');

Route::get('/projects/{project}/users/{user}/add', AddUserToProjectWebController::class)
     ->name('projects.users.add');

Route::get('/projects/{project}/users', IndexProjectUsersWebController::class)
     ->name('projects.users.index');

Route::get('/projects/{project}/users/edit', ModifyProjectUsersWebController::class)
     ->name('projects.users.edit');

Route::get('/projects/{project}/users/{user}/show', ShowProjectUserWebController::class)
     ->name('projects.users.show');

Route::post('/projects/{project}/globus/uploads', StoreGlobusUploadToProjectWebController::class)
     ->name('projects.globus.uploads.store');

Route::get('/projects/{project}/globus/uploads/create', CreateProjectGlobusUploadWebController::class)
     ->name('projects.globus.uploads.create');

Route::get('/projects/{project}/globus/uploads/{globusUpload}/delete', DeleteGlobusDownloadWebController::class)
     ->name('projects.globus.uploads.delete');

Route::delete('/projects/{project}/globus/uploads/{globusUpload}', DestroyGlobusDownloadWebController::class)
     ->name('projects.globus.uploads.destroy');

Route::get('/projects/{project}/globus/uploads/{globusUpload}/done',
    ShowGlobusDownloadToMarkAsCompleteWebController::class)
     ->name('projects.globus.uploads.done');

Route::post('/projects/{project}/globus/uploads/{globusUpload}/mark_done',
    MarkGlobusDownloadAsCompleteWebController::class)
     ->name('projects.globus.uploads.mark_done');

Route::get('/projects/{project}/globus/uploads/{globusUpload}', ShowProjectGlobusUploadWebController::class)
     ->name('projects.globus.uploads.show');

Route::get('/projects/{project}/globus/downloads', GlobusGetProjectDownloadLinkWebController::class)
     ->name('projects.globus.downloads');

Route::get('/projects/{project}/globus/status', ShowProjectGlobusUploadsStatusWebController::class)
     ->name('projects.globus.status');

Route::post('/projects/{project}/search', SearchProjectWebController::class)
     ->name('projects.search');
