<?php

use App\Http\Controllers\Web\Activities\Datatables\GetProjectActivitiesDatatableWebController;
use App\Http\Controllers\Web\Activities\IndexActivitiesWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/activities', IndexActivitiesWebController::class)
     ->name('projects.activities.index');

Route::get('/projects/{project}/datatables/activities', GetProjectActivitiesDatatableWebController::class)
     ->name('dt_get_project_activities');

