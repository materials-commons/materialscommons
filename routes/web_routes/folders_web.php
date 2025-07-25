<?php

use App\Http\Controllers\Web\Folders\ChooseProjectForCopyDestinationWebController;
use App\Http\Controllers\Web\Folders\CopyToDestinationWebController;
use App\Http\Controllers\Web\Folders\CreateFolderWebController;
use App\Http\Controllers\Web\Folders\Datatables\GetFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\Datatables\GetRootFolderDatatableWebController;
use App\Http\Controllers\Web\Folders\DeleteFolderWebController;
use App\Http\Controllers\Web\Folders\DestroyFolderWebController;
use App\Http\Controllers\Web\Folders\Filter\DTGetFilesForUserFilterWebController;
use App\Http\Controllers\Web\Folders\Filter\ShowFilesFilteredForUserWebController;
use App\Http\Controllers\Web\Folders\Filter\ShowFilterFilesByUserWebController;
use App\Http\Controllers\Web\Folders\GotoFolderByPathInParam;
use App\Http\Controllers\Web\Folders\IndexImagesWebController;
use App\Http\Controllers\Web\Folders\MoveFilesWebController;
use App\Http\Controllers\Web\Folders\RenameFolderWebController;
use App\Http\Controllers\Web\Folders\ShowAddUrlWebController;
use App\Http\Controllers\Web\Folders\ShowFoldersForCopyToProjectWebController;
use App\Http\Controllers\Web\Folders\ShowFolderWebController;
use App\Http\Controllers\Web\Folders\ShowRootFolderWebController;
use App\Http\Controllers\Web\Folders\ShowUploadFilesWebController;
use App\Http\Controllers\Web\Folders\StoreFolderWebController;
use App\Http\Controllers\Web\Folders\StoreUrlWebController;
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

Route::post('/projects/{project}/folders/{folder}/copy',
    CopyToDestinationWebController::class)
     ->name('projects.folders.copy-to');

Route::get('/projects/{leftProject}/{leftFolder}/{rightProject}/{rightFolder}/show-for-copy', ShowFoldersForCopyToProjectWebController::class)
     ->name('projects.folders.show-for-copy');

Route::get('/projects/{project}/folders/{file}/{copyType}/choose-project',
    ChooseProjectForCopyDestinationWebController::class)
     ->name('projects.folders.choose-project');

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

Route::get('/projects/{project}/folders/{dir}/destroy', DeleteFolderWebController::class)
     ->name('projects.folders.delete');

Route::get('/projects/{project}/folders/{dir}/rename', RenameFolderWebController::class)
     ->name('projects.folders.rename');
Route::put('/projects/{project}/folders/{dir}/rename', UpdateRenameFolderWebController::class)
     ->name('projects.folders.rename.update');

Route::get('/projects/{project}/folders/{folder}/index-images', IndexImagesWebController::class)
     ->name('projects.folders.index-images');

# URL Routes
Route::get('/projects/{project}/folders/{folder}/add-url', ShowAddUrlWebController::class)
     ->name('projects.folders.add-url');
Route::post('/projects/{project}/folders/{folder}/store-url', StoreUrlWebController::class)
     ->name('projects.folders.store-url');

# Filters
Route::get('/projects/{project}/filter/folders/by-user', ShowFilterFilesByUserWebController::class)
     ->name('projects.folders.filter.by-user');
Route::get('/projects/{project}/filter/folders/show-for-user/{user}', ShowFilesFilteredForUserWebController::class)
     ->name('projects.folders.filter.show-for-user');
Route::get('/projects/{project}/filter/folders/dt-show-for-user/{user}', DTGetFilesForUserFilterWebController::class)
     ->name('projects.folders.filter.dt_get_files_for_user_filter');
