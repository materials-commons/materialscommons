<?php

use App\Http\Controllers\Api\Datasets\SearchPublishedDataApiController;
use App\Http\Controllers\Api\Published\IndexPublishedAuthorsApiController;
use App\Http\Controllers\Api\Published\IndexPublishedDatasetTagsApiController;
use Illuminate\Support\Facades\Route;

Route::get('/published/tags', IndexPublishedDatasetTagsApiController::class);
Route::get('/published/authors', IndexPublishedAuthorsApiController::class);
Route::post('/published/data/search', SearchPublishedDataApiController::class);


