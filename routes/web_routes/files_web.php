<?php

use App\Http\Controllers\Web\Files\DownloadFileWebController;
use App\Http\Controllers\Web\Files\ImportSpreadsheetWebController;
use App\Http\Controllers\Web\Files\ShowFileEntitiesWebController;
use App\Http\Controllers\Web\Files\ShowFileWebController;
use App\Http\Controllers\Web\Files\UploadFilesWebController;
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

Route::get('/projects/{project}/files/{file}/import',
    ImportSpreadsheetWebController::class)->name('projects.files.import');