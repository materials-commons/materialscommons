<?php

use App\Http\Controllers\Api\Etl\GetFileByPathInProjectApiController;
use App\Http\Controllers\Api\Projects\CreateProjectApiController;
use Illuminate\Support\Facades\Route;

Route::post('/etl/getFileByPathInProject',
    GetFileByPathInProjectApiController::class)->name('api.etl.getFileByPathInProject');

// Add a second path to create a project that matches the expected path for the ETL
Route::post('/etl/createProject', CreateProjectApiController::class)->name('api.etl.createProject');
