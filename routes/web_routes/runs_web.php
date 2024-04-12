<?php

use App\Http\Controllers\Web\Projects\Runs\IndexScriptRunsWebController;
use App\Http\Controllers\Web\Projects\Runs\ShowScriptRunStatusWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/runs/index', IndexScriptRunsWebController::class)
     ->name('projects.runs.index');
Route::get('/projects/{project}/runs/{run}/show', ShowScriptRunStatusWebController::class)
     ->name('projects.runs.show');