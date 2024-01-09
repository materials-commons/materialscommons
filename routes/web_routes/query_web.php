<?php

use App\Http\Controllers\Web\Query\Partials\AddWherePartialWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/query-builder/add-where/{attrName}/{attrType}/{lastId}',
    AddWherePartialWebController::class)
     ->name('projects.query-builder.add-where');

