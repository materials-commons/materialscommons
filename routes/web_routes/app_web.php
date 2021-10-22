<?php

use App\Http\Controllers\Web\Trash\IndexTrashWebController;
use Illuminate\Support\Facades\Route;

Route::get('/trash', IndexTrashWebController::class)
     ->name('trash');

