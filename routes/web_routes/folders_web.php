<?php

use App\Http\Controllers\Web\Folders\CreateFolderWebController;
use App\Http\Controllers\Web\Folders\Datatables\GetFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\Datatables\GetRootFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\DeleteFolderWebController;
use App\Http\Controllers\Web\Folders\DestroyFolderWebController;
use App\Http\Controllers\Web\Folders\GotoFolderByPathInParam;
use App\Http\Controllers\Web\Folders\MoveFilesWebController;
use App\Http\Controllers\Web\Folders\RenameFolderWebController;
use App\Http\Controllers\Web\Folders\ShowFolderWebController;
use App\Http\Controllers\Web\Folders\ShowRootFolderWebController;
use App\Http\Controllers\Web\Folders\ShowUploadFilesWebController;
use App\Http\Controllers\Web\Folders\StoreFolderWebController;
use App\Http\Controllers\Web\Folders\UpdateMoveFilesWebController;
use App\Http\Controllers\Web\Folders\UpdateRenameFolderWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/folders/{folder}/create', CreateFolderWebController::class)
     ->name('projects.folders.create');
Route::post('/projects/{project}/folders/{folder}', StoreFolderWebController::class)
     ->name('projects.folders.store');

Route::get('/projects/{project}/goto_folder_by_path', GotoFolderByPathInParam::class)
     ->name('projects.folders.by_path');

Route::get('/projects/{project}/folders', ShowRootFolderWebController::class)
     ->name('projects.folders.index');

Route::get('/projects/{project}/folders/{folder}', ShowFolderWebController::class)
     ->name('projects.folders.show');

Route::get('/projects/{project}/folders/{directory}/upload', ShowUploadFilesWebController::class)
     ->name('projects.folders.upload');

Route::get('/projects/{project}/folders/{folder}/move', MoveFilesWebController::class)
     ->name('projects.folders.move');

Route::post('/projects/{project}/folders/{folder}/move', UpdateMoveFilesWebController::class)
     ->name('projects.folders.move.update');

Route::get('/projects/{project}/getRootFolder',
    GetRootFolderDatatableWebController::class)->name('projects.get_root_folder');
Route::get('/projects/{project}/folder/{folder}/getFolder', GetFolderDatatableWebController::class)
     ->name('projects.get_folder');

Route::get('/projects/{project}/folders/{dir}/delete', DeleteFolderWebController::class)
     ->name('projects.folders.delete');
Route::delete('/projects/{project}/folders/{dir}/destroy', DestroyFolderWebController::class)
     ->name('projects.folders.destroy');

Route::get('/projects/{project}/folders/{dir}/rename', RenameFolderWebController::class)
     ->name('projects.folders.rename');
Route::put('/projects/{project}/folders/{dir}/rename', UpdateRenameFolderWebController::class)
     ->name('projects.folders.rename.update');



