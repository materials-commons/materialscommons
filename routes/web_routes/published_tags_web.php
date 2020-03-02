<?php

use App\Http\Controllers\Web\Published\Tags\IndexPublishedDataTagsWebController;
use App\Http\Controllers\Web\Published\Tags\SearchPublishedDataTagsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/tags', IndexPublishedDataTagsWebController::class)
     ->name('tags.index');

Route::get('/tags/search', SearchPublishedDataTagsWebController::class)
     ->name('tags.search');

