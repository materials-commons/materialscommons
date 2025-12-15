<?php

use App\Http\Controllers\Web\Desktop\ShowDesktopWebController;
use App\Http\Controllers\Web\Desktop\SubmitTestUploadForDesktopWebController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/desktops/{desktop}/show', ShowDesktopWebController::class)
     ->name('projects.desktops.show');

Route::get('/projects/{project}/desktops/{desktop}/submit-test-upload', SubmitTestUploadForDesktopWebController::class)
     ->name('projects.desktops.submit-test-upload');
