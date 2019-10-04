<?php

use App\Http\Controllers\Web\Files\CreateExperimentFromSpreadsheetWebController;
use App\Http\Controllers\Web\Files\DownloadFileWebController;
use App\Http\Controllers\Web\Files\ShowFileEntitiesWebController;
use App\Http\Controllers\Web\Files\ShowFileWebController;
use App\Http\Controllers\Web\Files\UploadFilesWebController;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/files/{file}/upload', UploadFilesWebController::class)->name('projects.files.upload');

Route::get('/projects/{project}/files/{file}/show', ShowFileWebController::class)->name('projects.files.show');

Route::get('/projects/{project}/files/{file}/entities',
    ShowFileEntitiesWebController::class)->name('projects.files.entities');

Route::get('/projects/{project}/files/{file}/activities',
    ShowFileEntitiesWebController::class)->name('projects.files.activities');

Route::get('/projects/{project}/files/{file}/attributes',
    ShowFileEntitiesWebController::class)->name('projects.files.attributes');

Route::get('/projects/{project}/files/{file}/experiments',
    ShowFileEntitiesWebController::class)->name('projects.files.experiments');

Route::get('/projects/{project}/files/{file}/download',
    DownloadFileWebController::class)->name('projects.files.download');

Route::get('/projects/{project}/files/{file}/create-experiment', function(Project $project, File $file) {
    return view('app.files.import', compact('project', 'file'));
});

Route::post('/projects/{project}/files/{file}/create-experiment',
    CreateExperimentFromSpreadsheetWebController::class)->name('projects.files.create-experiment');