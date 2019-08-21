<?php

use App\Http\Controllers\Api\Projects\Activities\IndexProjectActivitiesApiController;
use App\Http\Controllers\Api\Projects\CreateProjectApiController;
use App\Http\Controllers\Api\Projects\DeleteProjectApiController;
use App\Http\Controllers\Api\Projects\IndexProjectsApiController;
use App\Http\Controllers\Api\Projects\ShowProjectApiController;
use App\Http\Controllers\Api\Projects\UpdateProjectApiController;
use Illuminate\Support\Facades\Route;

Route::post('/projects', CreateProjectApiController::class);
Route::put('/projects/{project}', UpdateProjectApiController::class);
Route::delete('/projects/{project}', DeleteProjectApiController::class);
Route::get('/projects/{project}', ShowProjectApiController::class);
Route::get('/projects', IndexProjectsApiController::class);

Route::get('/projects/{project}/activities', IndexProjectActivitiesApiController::class);