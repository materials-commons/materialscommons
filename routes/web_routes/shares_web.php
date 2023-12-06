<?php

use App\Http\Controllers\Web\Shares\DownloadSharedProjectByPathFileWebController;
use Illuminate\Support\Facades\Route;

Route::get('/shares/project/{project}/files/download', DownloadSharedProjectByPathFileWebController::class)
     ->name('shares.project.files.download');
