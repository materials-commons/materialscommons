<?php

use App\Http\Controllers\Web\Experiments\CreateExperimentWebController;
use App\Http\Controllers\Web\Experiments\DeleteExperimentWebController;
use App\Http\Controllers\Web\Experiments\EditExperimentWebController;
use App\Http\Controllers\Web\Experiments\IndexExperimentsWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentWebController;
use App\Http\Controllers\Web\Experiments\StoreExperimentWebController;
use App\Http\Controllers\Web\Experiments\UpdateExperimentWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('/projects/{project}')->group(function () {
    Route::get('/experiments/create', CreateExperimentWebController::class)->name('projects.experiments.create');
    Route::post('/experiments', StoreExperimentWebController::class)->name('projects.experiments.store');

    Route::get('/experiments', IndexExperimentsWebController::class)->name('projects.experiments.index');
    Route::get('/experiments/{experiment}', ShowExperimentWebController::class)->name('projects.experiments.show');

    Route::patch('/experiments/{experiment}',
        UpdateExperimentWebController::class)->name('projects.experiments.update');
    Route::get('/experiments/{experiment}/edit', EditExperimentWebController::class)->name('projects.experiments.edit');

    Route::delete('/experiments/{experiment}',
        DeleteExperimentWebController::class)->name('projects.experiments.destroy');
});