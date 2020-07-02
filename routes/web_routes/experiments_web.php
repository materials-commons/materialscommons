<?php

use App\Http\Controllers\Web\Experiments\CreateExperimentWebController;
use App\Http\Controllers\Web\Experiments\Datatables\GetExperimentActivitiesDatatableWebController;
use App\Http\Controllers\Web\Experiments\Datatables\GetExperimentEntitiesDatatableWebController;
use App\Http\Controllers\Web\Experiments\DeleteExperimentWebController;
use App\Http\Controllers\Web\Experiments\DestroyExperimentWebController;
use App\Http\Controllers\Web\Experiments\EditExperimentWebController;
use App\Http\Controllers\Web\Experiments\IndexExperimentsWebController;
use App\Http\Controllers\Web\Experiments\ReloadExperimentWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentDataDictionaryWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEntitiesWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentWebController;
use App\Http\Controllers\Web\Experiments\ShowReloadExperimentWebController;
use App\Http\Controllers\Web\Experiments\StoreExperimentWebController;
use App\Http\Controllers\Web\Experiments\UpdateExperimentWebController;
use App\Http\Controllers\Web\Experiments\UploadExcelFileWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('/projects/{project}')->group(function () {
    Route::get('/experiments/create', CreateExperimentWebController::class)->name('projects.experiments.create');
    Route::post('/experiments', StoreExperimentWebController::class)->name('projects.experiments.store');

    Route::get('/experiments/upload-excel', UploadExcelFileWebController::class)
         ->name('projects.experiments.upload-excel');

    Route::get('/experiments', IndexExperimentsWebController::class)->name('projects.experiments.index');
    Route::get('/experiments/{experiment}', ShowExperimentWebController::class)->name('projects.experiments.show');

    Route::patch('/experiments/{experiment}',
        UpdateExperimentWebController::class)->name('projects.experiments.update');
    Route::get('/experiments/{experiment}/edit', EditExperimentWebController::class)->name('projects.experiments.edit');

    Route::get('/experiments/{experiment}/delete', DeleteExperimentWebController::class)
         ->name('projects.experiments.delete');

    Route::delete('/experiments/{experiment}',
        DestroyExperimentWebController::class)->name('projects.experiments.destroy');

    Route::get('/experiments/{experiment}/entities', ShowExperimentEntitiesWebController::class)
         ->name('projects.experiments.entities-tab');

    Route::get('/experiments/{experiment}/data-dictionary', ShowExperimentDataDictionaryWebController::class)
         ->name('projects.experiments.data-dictionary');

//    Route::get('/experiments/{experiment}/activities', function (Project $project, Experiment $experiment) {
//        $excelFilesCount = $project->files()
//                                   ->where('mime_type',
//                                       "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
//                                   ->where('current', true)
//                                   ->count();
//        return view('app.projects.experiments.show', compact('project', 'experiment', 'excelFilesCount'));
//    })->name('projects.experiments.activities-tab');

    Route::get('/experiments/{experiment}/reload', ShowReloadExperimentWebController::class)
         ->name('projects.experiments.show-reload');

    Route::put('/experiments/{experiment}/reload', ReloadExperimentWebController::class)
         ->name('projects.experiments.reload');

    Route::get('/experiments/{experiment}/datables/entities', GetExperimentEntitiesDatatableWebController::class)
         ->name('dt_get_experiment_entities');

    Route::get('/experiments/{experiment}/datables/activities', GetExperimentActivitiesDatatableWebController::class)
         ->name('dt_get_experiment_activities');
});