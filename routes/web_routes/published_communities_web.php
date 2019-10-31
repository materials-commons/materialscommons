<?php

use App\Http\Controllers\Web\Published\Communities\IndexPublishedCommunitiesWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexPublishedCommunitiesWebController::class)->name('communities.index');

