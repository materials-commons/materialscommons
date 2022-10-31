<?php

use App\Http\Controllers\Web\Files\CompareFilesWebController;
use App\Http\Controllers\Web\Files\CreateExperimentFromSpreadsheetWebController;
use App\Http\Controllers\Web\Files\DeleteFileWebController;
use App\Http\Controllers\Web\Files\DestroyFileWebController;
use App\Http\Controllers\Web\Files\DisplayFileWebController;
use App\Http\Controllers\Web\Files\DownloadFileWebController;
use App\Http\Controllers\Web\Files\RenameFileWebController;
use App\Http\Controllers\Web\Files\SetAsActiveFileVersionWebController;
use App\Http\Controllers\Web\Files\ShowFileActivitiesWebController;
use App\Http\Controllers\Web\Files\ShowFileAttributesWebController;
use App\Http\Controllers\Web\Files\ShowFileEntitiesWebController;
use App\Http\Controllers\Web\Files\ShowFileExperimentsWebController;
use App\Http\Controllers\Web\Files\ShowFileVersionsWebController;
use App\Http\Controllers\Web\Files\ShowFileWebController;
use App\Http\Controllers\Web\Files\Trashcan\DeleteDirectoryFromTrashcanWebController;
use App\Http\Controllers\Web\Files\Trashcan\DeleteFileFromTrashcanWebController;
use App\Http\Controllers\Web\Files\Trashcan\EmptyTrashcanWebController;
use App\Http\Controllers\Web\Files\Trashcan\IndexFilesTrashcanWebController;
use App\Http\Controllers\Web\Files\Trashcan\RestoreDirectoryFromTrashcanWebController;
use App\Http\Controllers\Web\Files\Trashcan\RestoreFileFromTrashcanWebController;
use App\Http\Controllers\Web\Files\UpdateRenameFileWebController;
use App\Http\Controllers\Web\Files\UploadFilesWebController;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/files/{file}/upload', UploadFilesWebController::class)
     ->name('projects.files.upload');

Route::get('/projects/{project}/files/{file}/show', ShowFileWebController::class)
     ->name('projects.files.show');

Route::get('/projects/{project}/files/{file}/entities', ShowFileEntitiesWebController::class)
     ->name('projects.files.entities');

Route::get('/projects/{project}/files/{file}/activities', ShowFileActivitiesWebController::class)
     ->name('projects.files.activities');

Route::get('/projects/{project}/files/{file}/attributes', ShowFileAttributesWebController::class)
     ->name('projects.files.attributes');

Route::get('/projects/{project}/files/{file}/experiments', ShowFileExperimentsWebController::class)
     ->name('projects.files.experiments');

Route::get('/projects/{project}/files/{file}/versions', ShowFileVersionsWebController::class)
     ->name('projects.files.versions');

Route::get('/projects/{project}/files/{file}/set-active', SetAsActiveFileVersionWebController::class)
     ->name('projects.files.set-active');

Route::get('/projects/{project}/files/{file1}/{file2}/compare', CompareFilesWebController::class)
     ->name('projects.files.compare');

Route::get('/projects/{project}/files/{file}/display', DisplayFileWebController::class)
     ->name('projects.files.display');

Route::get('/projects/{project}/files/{file}/download', DownloadFileWebController::class)
     ->name('projects.files.download');

Route::get('/projects/{project}/files/{file}/create-experiment', function (Project $project, File $file) {
    return view('app.files.import', compact('project', 'file'));
});

Route::get('/projects/{project}/files/{file}/delete', DeleteFileWebController::class)
     ->name('projects.files.delete');
Route::delete('/projects/{project}/files/{file}/destroy', DestroyFileWebController::class)
     ->name('projects.files.destroy');
Route::get('/projects/{project}/files/{file}/destroy', DestroyFileWebController::class)
     ->name('projects.files.destroy');

Route::get('/projects/{project}/files/{file}/rename', RenameFileWebController::class)
     ->name('projects.files.rename');
Route::put('/projects/{project}/files/{file}/rename', UpdateRenameFileWebController::class)
     ->name('projects.files.rename.update');

//Route::get('/projects/{project}/file_path/{path}', function (Project $project, $path) {
//    dd($path);
//})->where('path', '(.*)');

Route::post('/projects/{project}/files/{file}/create-experiment',
    CreateExperimentFromSpreadsheetWebController::class)
     ->name('projects.files.create-experiment');

// Trashcan
Route::get('/projects/{project}/trashcan', IndexFilesTrashcanWebController::class)
     ->name('projects.trashcan.index');

Route::get('/projects/{project}/trashcan/empty', EmptyTrashcanWebController::class)
     ->name('projects.trashcan.empty');

Route::get('/projects/{project}/trashcan/dir/{dir}/restore', RestoreDirectoryFromTrashcanWebController::class)
     ->name('projects.trashcan.dir.restore');
Route::get('/projects/{project}/trashcan/dir/{dir}/delete', DeleteDirectoryFromTrashcanWebController::class)
     ->name('projects.trashcan.dir.delete');

Route::get('/projects/{project}/trashcan/file/{file}/restore', RestoreFileFromTrashcanWebController::class)
     ->name('projects.trashcan.file.restore');
Route::get('/projects/{project}/trashcan/file/{file}/delete', DeleteFileFromTrashcanWebController::class)
     ->name('projects.trashcan.file.delete');
