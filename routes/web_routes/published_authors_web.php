<?php

use App\Http\Controllers\Web\Published\Authors\IndexPublishedAuthorsWebController;
use App\Http\Controllers\Web\Published\Authors\SearchPublishedAuthorsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/authors', IndexPublishedAuthorsWebController::class)
     ->name('authors.index');

Route::get('/authors/search', SearchPublishedAuthorsWebController::class)
     ->name('authors.search');
