<?php

use App\Http\Controllers\Web\Folders\Datatables\GetFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\Datatables\GetRootFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\ShowFolderWebController;
use App\Http\Controllers\Web\Folders\ShowRootFolderWebController;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/folders', ShowRootFolderWebController::class)->name('projects.folders.index');
Route::get('/projects/{project}/folders/{folder}', ShowFolderWebController::class)->name('projects.folders.show');

Route::get('/projects/{project}/folders/{directory}/upload', function (Project $project, File $directory) {
    return view('app.projects.folders.upload', compact('project', 'directory'));
})->name('projects.folders.upload');

Route::get('/projects/{project}/getRootFolder',
    GetRootFolderDatatableWebController::class)->name('projects.get_root_folder');
Route::get('/projects/{project}/folder/{folder}/getFolder',
    GetFolderDatatableWebController::class)->name('projects.get_folder');

