<?php

use App\Exports\EntitiesExport;
use App\Http\Controllers\Web\Entities\CompareEntitiesWebController;
use App\Http\Controllers\Web\Entities\CompareActivitiesWebController;
use App\Http\Controllers\Web\Entities\CreateProjectEntityWebController;
use App\Http\Controllers\Web\Entities\Datatables\GetProjectEntitiesDatatableWebController;
use App\Http\Controllers\Web\Entities\IndexEntitiesWebController;
use App\Http\Controllers\Web\Entities\Mql\RunMqlQueryWebController;
use App\Http\Controllers\Web\Entities\Mql\ShowMqlQueryWebController;
use App\Http\Controllers\Web\Entities\ShowEntityAttributesWebController;
use App\Http\Controllers\Web\Entities\ShowEntityByNameSpreadWebController;
use App\Http\Controllers\Web\Entities\ShowEntityFilesWebController;
use App\Http\Controllers\Web\Entities\ShowEntitySpreadWebController;
use App\Http\Controllers\Web\Entities\ShowEntityWebController;
use App\Http\Controllers\Web\Entities\StoreProjectEntityWebController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/projects/{project}/entities', IndexEntitiesWebController::class)
     ->name('projects.entities.index');
Route::get('/projects/{project}/computations/entities', IndexEntitiesWebController::class)
     ->name('projects.computations.entities.index');

Route::get('/projects/{project}/datatables/entities', GetProjectEntitiesDatatableWebController::class)
     ->name('dt_get_project_entities');

Route::get('/projects/{project}/entities/{entity1}/compare/{entity2}', CompareEntitiesWebController::class)
     ->name('projects.entities.compare');

Route::get('/projects/{project}/activities/compare', CompareActivitiesWebController::class)
     ->name('projects.activities.compare');

Route::get('/projects/{project}/entitites/create', CreateProjectEntityWebController::class)
     ->name('projects.entities.create');

Route::post('/projects/{project}/entities', StoreProjectEntityWebController::class)
     ->name('projects.entities.store');

Route::get('/projects/{project}/entities/{entity}/spread', ShowEntitySpreadWebController::class)
     ->name('projects.entities.show-spread');
Route::get('/projects/{project}/computations/entities/{entity}/spread', ShowEntitySpreadWebController::class)
     ->name('projects.computations.entities.show-spread');

Route::get('/projects/{project}/experiments/{experiment}/entities-by-name/spread',
    ShowEntityByNameSpreadWebController::class)
     ->name('projects.experiments.entities.by-name.spread');
Route::get('/projects/{project}/experiments/{experiment}/computations/entities-by-name/spread',
    ShowEntityByNameSpreadWebController::class)
     ->name('projects.experiments.computations.entities.by-name.spread');

Route::get('/projects/{project}/entities/{entity}', ShowEntityWebController::class)
     ->name('projects.entities.show');

Route::get('/projects/{project}/computations/entities/{entity}', ShowEntityWebController::class)
     ->name('projects.computations.entities.show');

Route::get('/projects/{project}/entities/{entity}/files', ShowEntityFilesWebController::class)
     ->name('projects.entities.files');
Route::get('/projects/{project}/entities/{entity}/attributes', ShowEntityAttributesWebController::class)
     ->name('projects.entities.attributes');

Route::get('/projects/{project}/experiments/{experiment}/entities/{entity}', ShowEntitySpreadWebController::class)
     ->name('projects.experiments.entities.show');
Route::get('/projects/{project}/experiments/{experiment}/entities/{entity}/files', ShowEntityFilesWebController::class)
     ->name('projects.experiments.entities.files');
Route::get('/projects/{project}/experiments/{experiment}/entities/{entity}/attributes',
    ShowEntityAttributesWebController::class)
     ->name('projects.experiments.entities.attributes');

Route::get('/projects/{project}/export-entities', function ($projectId) {
    return Excel::download(new EntitiesExport($projectId), 'entities.xlsx');
})->name('projects.entities-export');

# MQL
Route::post('/projects/{project}/mql-show', ShowMqlQueryWebController::class)
     ->name('projects.entities.mql.show');

Route::post('/projects/{project}/mql-run', RunMqlQueryWebController::class)
     ->name('projects.entities.mql.run');
