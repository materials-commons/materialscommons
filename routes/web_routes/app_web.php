<?php

use App\Http\Controllers\Web\Trash\IndexTrashWebController;
use App\Http\Controllers\Web\Trash\RestoreProjectFromTrashWebController;
use Illuminate\Support\Facades\Route;

Route::get('/trash', IndexTrashWebController::class)
     ->name('trash');

Route::get('/trash/{project}/restore', RestoreProjectFromTrashWebController::class)
     ->name('trash.project.restore');

