<?php

use App\Http\Controllers\Web\Files\UploadFilesWebController;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/folders/{directory}/upload', function (Project $project, File $directory) {
    error_log('here i am');
    return view('app.projects.folders.upload', compact('project', 'directory'));
})->name('projects.folders.upload');

Route::post('/projects/{project}/files/{file}/upload', UploadFilesWebController::class)->name('projects.files.upload');

