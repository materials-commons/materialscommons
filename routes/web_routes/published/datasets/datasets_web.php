<?php

use App\Models\Dataset;
use Illuminate\Support\Facades\Route;

Route::get('/datasets/{dataset}/entities', function (Dataset $dataset) {
    return view('public.datasets.show', compact('dataset'));
})->name('datasets.entities.index');

Route::get('/datasets/{dataset}/activities', function (Dataset $dataset) {
    return view('public.datasets.show', compact('dataset'));
})->name('datasets.activities.index');


