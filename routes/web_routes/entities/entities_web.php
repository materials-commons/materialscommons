<?php

use App\Http\Controllers\Web\Entities\Datatables\GetProjectEntitiesDatatableWebController;
use App\Http\Controllers\Web\Entities\IndexEntitiesWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/entities', IndexEntitiesWebController::class)
     ->name('projects.entities.index');
Route::get('/projects/{project}/datatables/entities', GetProjectEntitiesDatatableWebController::class)
     ->name('dt_get_project_entities');

