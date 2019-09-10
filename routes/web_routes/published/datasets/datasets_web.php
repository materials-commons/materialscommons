<?php

use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetActivitiesDatatableWebController;
use App\Http\Controllers\Web\Published\Datasets\Datatables\GetDatasetEntitiesDatatableWebController;
use App\Models\Dataset;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}/entities', function (Dataset $dataset) {
    return view('public.datasets.show', compact('dataset'));
})->name('datasets.entities.index');

Route::get('/datasets/{dataset}/activities', function (Dataset $dataset) {
    return view('public.datasets.show', compact('dataset'));
})->name('datasets.activities.index');

Route::get('/datasets/{dataset}/datatables/activities', GetDatasetActivitiesDatatableWebController::class)
     ->name('dt_get_dataset_activities');
Route::get('/datasets/{dataset}/datatables/entities', GetDatasetEntitiesDatatableWebController::class)
     ->name('dt_get_dataset_entities');


