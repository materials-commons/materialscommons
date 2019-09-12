<?php

use App\Http\Controllers\Web\Activities\Datatables\GetProjectActivitiesDatatableWebController;
use App\Http\Controllers\Web\Activities\IndexActivitiesWebController;
use App\Http\Controllers\Web\Activities\ShowActivityFilesWebController;
use App\Http\Controllers\Web\Activities\ShowActivityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/activities', IndexActivitiesWebController::class)
     ->name('projects.activities.index');

Route::get('/projects/{project}/datatables/activities', GetProjectActivitiesDatatableWebController::class)
     ->name('dt_get_project_activities');

Route::get('/projects/{project}/activities/{activity}', ShowActivityWebController::class)
     ->name('projects.activities.show');
Route::get('/projects/{project}/activities/{activity}/files', ShowActivityFilesWebController::class)
     ->name('projects.activities.files');

Route::get('/projects/{project}/experiments/{experiment}/activities/{activity}', ShowActivityWebController::class)
     ->name('projects.experiments.activities.show');
Route::get('/projects/{project}/experiments/{experiment}/activities/{activity}/files',
    ShowActivityFilesWebController::class)->name('projects.experiments.activities.files');
