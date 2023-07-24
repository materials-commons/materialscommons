<?php

use App\Http\Controllers\Web\Activities\Calculations\IndexCalculationsWebController;
use App\Http\Controllers\Web\Activities\Calculations\ShowCalculationWebController;
use App\Http\Controllers\Web\Activities\Datatables\GetProjectActivitiesDatatableWebController;
use App\Http\Controllers\Web\Activities\IndexActivitiesWebController;
use App\Http\Controllers\Web\Activities\ShowActivityByNameWebController;
use App\Http\Controllers\Web\Activities\ShowActivityEntitiesWebController;
use App\Http\Controllers\Web\Activities\ShowActivityFilesWebController;
use App\Http\Controllers\Web\Activities\ShowActivityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/activities/calculations', IndexCalculationsWebController::class)
     ->name('projects.activities.calculations.index');

Route::get('/projects/{project}/activities/calculations/{activity}', ShowCalculationWebController::class)
     ->name('projects.activities.calculations.show');

Route::get('/projects/{project}/activities', IndexActivitiesWebController::class)
     ->name('projects.activities.index');

Route::get('/projects/{project}/datatables/activities', GetProjectActivitiesDatatableWebController::class)
     ->name('dt_get_project_activities');

Route::get('/projects/{project}/activities/byname/{name}', ShowActivityByNameWebController::class)
     ->name('projects.activities.show-by-name');

Route::get('/projects/{project}/activities/{activity}', ShowActivityWebController::class)
     ->name('projects.activities.show');
Route::get('/projects/{project}/activities/{activity}/files', ShowActivityFilesWebController::class)
     ->name('projects.activities.files');
Route::get('/projects/{project}/activities/{activity}/entities',
    ShowActivityEntitiesWebController::class)->name('projects.activities.entities');

Route::get('/projects/{project}/experiments/{experiment}/activities/{activity}', ShowActivityWebController::class)
     ->name('projects.experiments.activities.show');
Route::get('/projects/{project}/experiments/{experiment}/activities/{activity}/files',
    ShowActivityFilesWebController::class)->name('projects.experiments.activities.files');
Route::get('/projects/{project}/experiments/{experiment}/activities/{activity}/entities',
    ShowActivityEntitiesWebController::class)->name('projects.experiments.activities.entities');


