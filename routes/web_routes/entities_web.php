<?php

use App\Http\Controllers\Web\Entities\Datatables\GetProjectEntitiesDatatableWebController;
use App\Http\Controllers\Web\Entities\IndexEntitiesWebController;
use App\Http\Controllers\Web\Entities\ShowEntityFilesWebController;
use App\Http\Controllers\Web\Entities\ShowEntityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/entities', IndexEntitiesWebController::class)
     ->name('projects.entities.index');
Route::get('/projects/{project}/datatables/entities', GetProjectEntitiesDatatableWebController::class)
     ->name('dt_get_project_entities');

Route::get('/projects/{project}/entities/{entity}', ShowEntityWebController::class)
     ->name('projects.entities.show');
Route::get('/projects/{project}/entities/{entity}/files', ShowEntityFilesWebController::class)
     ->name('projects.entities.files');

Route::get('/projects/{project}/experiments/{experiment}/entities/{entity}', ShowEntityWebController::class)
     ->name('projects.experiments.entities.show');
Route::get('/projects/{project}/experiments/{experiment}/entities/{entity}/files', ShowEntityFilesWebController::class)
     ->name('projects.experiments.entities.files');


