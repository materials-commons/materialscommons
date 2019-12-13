<?php

use App\Http\Controllers\Web\Users\ShowUserWebController;
use Illuminate\Support\Facades\Route;

Route::get('/users/{user}', ShowUserWebController::class)
     ->name('users.show');