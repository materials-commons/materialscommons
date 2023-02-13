<?php

use App\Http\Controllers\Web\Published\UHCSDB\ShowUHCSDBWebController;
use Illuminate\Support\Facades\Route;

Route::get('/reference/uhcsdb', ShowUHCSDBWebController::class)
     ->name('public.uhcsdb.show');