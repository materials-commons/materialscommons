<?php

use App\Http\Controllers\Web\Published\Authors\IndexPublishedAuthorsWebController;
use App\Http\Controllers\Web\Published\Authors\SearchPublishedAuthorsWebController;
use App\Http\Controllers\Web\Published\Authors\ShowPublishedAuthorWebController;
use Illuminate\Support\Facades\Route;

Route::get('/authors', IndexPublishedAuthorsWebController::class)
     ->name('public.authors.index');

Route::get('/authors/search', SearchPublishedAuthorsWebController::class)
     ->name('public.authors.search');

Route::get('/authors/{user}', ShowPublishedAuthorWebController::class)
     ->name('public.authors.show');
