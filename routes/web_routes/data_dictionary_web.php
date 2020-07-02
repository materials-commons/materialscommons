<?php

use App\Http\Controllers\Web\DataDictionary\IndexDataDictionaryForAllUserProjectsWebController;
use App\Http\Controllers\Web\DataDictionary\IndexDataDictionaryForProjectWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('/projects/{project}')->group(function () {
    Route::get('/data-dictionary', IndexDataDictionaryForProjectWebController::class)
         ->name('projects.datadictionary.index');
});

Route::prefix('/projects')->group(function () {
    Route::get('/datadictionary', IndexDataDictionaryForAllUserProjectsWebController::class)
         ->name('datadictionary.index');
});

