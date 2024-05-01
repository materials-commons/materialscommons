<?php

use App\Http\Controllers\Web\Triggers\CreateTriggerWebController;
use App\Http\Controllers\Web\Triggers\DestroyTriggerWebController;
use App\Http\Controllers\Web\Triggers\EditTriggerWebController;
use App\Http\Controllers\Web\Triggers\IndexTriggersWebController;
use App\Http\Controllers\Web\Triggers\ShowTriggerWebController;
use App\Http\Controllers\Web\Triggers\StoreTriggerWebController;
use App\Http\Controllers\Web\Triggers\UpdateTriggerWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/triggers', IndexTriggersWebController::class)
    ->name('projects.triggers.index');

Route::get('/projects/{project}/triggers/{trigger}', ShowTriggerWebController::class)
    ->name('projects.triggers.show');

Route::get('/projects/{project}/triggers/create', CreateTriggerWebController::class)
    ->name('projects.triggers.create');

Route::post('/projects/{project}/triggers', StoreTriggerWebController::class)
    ->name('projects.triggers.store');

Route::get('/projects/{project}/triggers/{trigger}/edit', EditTriggerWebController::class)
    ->name('projects.triggers.edit');

Route::patch('/projects/{project}/triggers/{trigger}', UpdateTriggerWebController::class)
    ->name('projects.triggers.update');

Route::delete('/projects/{project}/triggers/{trigger}', DestroyTriggerWebController::class)
    ->name('projects.triggers.destroy');

