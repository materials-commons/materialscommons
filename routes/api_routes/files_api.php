<?php

use App\Http\Controllers\Api\Camera\UploadCameraPhotoApiController;
use App\Http\Controllers\Api\Files\CreateFileApiController;
use App\Http\Controllers\Api\Files\DeleteFileApiController;
use App\Http\Controllers\Api\Files\DownloadFileApiController;
use App\Http\Controllers\Api\Files\GetFileByPathApiController;
use App\Http\Controllers\Api\Files\IndexAllProjectsForFilesMatchingApiController;
use App\Http\Controllers\Api\Files\IndexProjectForFilesMatchingApiController;
use App\Http\Controllers\Api\Files\MoveFileApiController;
use App\Http\Controllers\Api\Files\RenameFileApiController;
use App\Http\Controllers\Api\Files\Scripts\RunScriptApiController;
use App\Http\Controllers\Api\Files\SetAsActiveFileVersionApiController;
use App\Http\Controllers\Api\Files\ShowFileApiController;
use App\Http\Controllers\Api\Files\ShowFileVersionsApiController;
use App\Http\Controllers\Api\Files\UpdateFileApiController;
use App\Http\Controllers\Api\Files\UploadFileApiController;
use App\Http\Controllers\Api\Files\UploadFileNamedApiController;
use App\Http\Controllers\Api\Files\UploadFileToPathApiController;
use Illuminate\Support\Facades\Route;

Route::post('/projects/{project}/files/upload-to-path', UploadFileToPathApiController::class);
Route::post('/files', CreateFileApiController::class);
Route::post("/projects/files/matching", IndexAllProjectsForFilesMatchingApiController::class);
Route::post("/projects/{project}/files/matching", IndexProjectForFilesMatchingApiController::class);
Route::put('/files/{file}', UpdateFileApiController::class);
Route::put('/projects/{project}/files/{file}/make_active', SetAsActiveFileVersionApiController::class);
Route::delete('/projects/{project}/files/{file}', DeleteFileApiController::class);
Route::get('/projects/{project}/files/{file}', ShowFileApiController::class);
Route::get('/projects/{project}/files/{file}/versions', ShowFileVersionsApiController::class);
Route::post('/files/{file}/move', MoveFileApiController::class);
Route::post('/files/{file}/rename', RenameFileApiController::class);
Route::get('/projects/{project}/files/{file}/download', DownloadFileApiController::class);
Route::post('/files/by_path', GetFileByPathApiController::class);
Route::post('/projects/{project}/files/{file}/run-script', RunScriptApiController::class);
Route::post('/projects/{project}/files/{file}/upload', UploadFileApiController::class)
     ->name('api.projects.files.upload');
Route::post('/projects/{project}/files/{file}/upload/{name}', UploadFileNamedApiController::class)
     ->name('api.projects.files.upload.named');
Route::post('/upload-camera-image', UploadCameraPhotoApiController::class)->name('api.upload-camera-image');

