<?php

use App\Http\Controllers\Web\Experiments\CreateExperimentWebController;
use App\Http\Controllers\Web\Experiments\Datatables\GetExperimentActivitiesDatatableWebController;
use App\Http\Controllers\Web\Experiments\Datatables\GetExperimentEntitiesDatatableWebController;
use App\Http\Controllers\Web\Experiments\DeleteExperimentWebController;
use App\Http\Controllers\Web\Experiments\DestroyExperimentWebController;
use App\Http\Controllers\Web\Experiments\EditExperimentWebController;
use App\Http\Controllers\Web\Experiments\IndexExperimentsWebController;
use App\Http\Controllers\Web\Experiments\ReloadExperimentWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentActivitiesDataDictionaryWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEntitiesDataDictionaryWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEntitiesWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEtlRunLogWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEtlRunsWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentEtlRunWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentOverviewWebController;
use App\Http\Controllers\Web\Experiments\ShowExperimentWorkflowWebController;
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

    Route::patch('/experiments/{experiment}',
        UpdateExperimentWebController::class)->name('projects.experiments.update');
    Route::get('/experiments/{experiment}/edit', EditExperimentWebController::class)->name('projects.experiments.edit');

    Route::get('/experiments/{experiment}/delete', DeleteExperimentWebController::class)
         ->name('projects.experiments.delete');

    Route::delete('/experiments/{experiment}',
        DestroyExperimentWebController::class)->name('projects.experiments.destroy');

    Route::get('/experiments/{experiment}', ShowExperimentOverviewWebController::class)
         ->name('projects.experiments.show');

    Route::get('/experiments/{experiment}/entities', ShowExperimentEntitiesWebController::class)
         ->name('projects.experiments.entities');

    Route::get('/experiments/{experiment}/workflow', ShowExperimentWorkflowWebController::class)
         ->name('projects.experiments.workflow');

    Route::get('/experiments/{experiment}/etl_runs', ShowExperimentEtlRunsWebController::class)
         ->name('projects.experiments.etl_runs');

    Route::get('/experiments/{experiment}/data-dictionary/activities',
        ShowExperimentActivitiesDataDictionaryWebController::class)
         ->name('projects.experiments.data-dictionary.activities');

    Route::get('/experiments/{experiment}/data-dictionary/entities',
        ShowExperimentEntitiesDataDictionaryWebController::class)
         ->name('projects.experiments.data-dictionary.entities');

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

    Route::get('/experiments/{experiment}/etl_run/{etlRun}/log', ShowExperimentEtlRunLogWebController::class)
         ->name('projects.experiments.etl_run.show-log');

    Route::get('/experiments/{experiment}/etl_run/{etlRun}', ShowExperimentEtlRunWebController::class)
         ->name('projects.experiments.etl_run.show');
});