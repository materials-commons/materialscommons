<?php

use App\Http\Controllers\Api\Globus\CloseGlobusTransferForProjectApiController;
use App\Http\Controllers\Api\Globus\Downloads\CreateGlobusDownloadApiController;
use App\Http\Controllers\Api\Globus\Downloads\DeleteGlobusDownloadApiController;
use App\Http\Controllers\Api\Globus\Downloads\GetGlobusDownloadApiController;
use App\Http\Controllers\Api\Globus\Downloads\IndexProjectGlobusDownloadsApiController;
use App\Http\Controllers\Api\Globus\OpenGlobusTransferForProjectApiController;
use App\Http\Controllers\Api\Globus\Uploads\CreateGlobusUploadApiController;
use App\Http\Controllers\Api\Globus\Uploads\DeleteGlobusUploadApiController;
use App\Http\Controllers\Api\Globus\Uploads\IndexProjectGlobusUploadsApiController;
use App\Http\Controllers\Api\Globus\Uploads\MarkGlobusUploadAsCompleteApiController;
use Illuminate\Support\Facades\Route;

// Globus Uploads
Route::get('/projects/{project}/globus/uploads', IndexProjectGlobusUploadsApiController::class);
Route::post('/globus/uploads', CreateGlobusUploadApiController::class);
Route::put('/globus/{globus}/uploads/complete', MarkGlobusUploadAsCompleteApiController::class);
Route::delete('/projects/{project}/globus/{globus}/uploads', DeleteGlobusUploadApiController::class);

// Globus Downloads
Route::get('/projects/{project}/globus/downloads', IndexProjectGlobusDownloadsApiController::class);
Route::post('/globus/downloads', CreateGlobusDownloadApiController::class);
Route::delete('/projects/{project}/globus/{globus}/downloads', DeleteGlobusDownloadApiController::class);
Route::get('/projects/{project}/globus/downloads/{download}', GetGlobusDownloadApiController::class);

// Globus NG
Route::get('/projects/{project}/globus/open', OpenGlobusTransferForProjectApiController::class);
Route::get('/projects/{project}/globus/close', CloseGlobusTransferForProjectApiController::class);