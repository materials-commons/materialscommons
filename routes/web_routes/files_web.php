<?php

use App\Http\Controllers\Web\Files\ShowFileWebController;
use App\Http\Controllers\Web\Files\UploadFilesWebController;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/files/{file}/upload', UploadFilesWebController::class)->name('projects.files.upload');

Route::get('/projects/{project}/files/{file}/show', ShowFileWebController::class)->name('projects.files.show');