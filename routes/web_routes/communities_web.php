<?php

use App\Http\Controllers\Web\Communities\CreateCommunityWebController;
use App\Http\Controllers\Web\Communities\Datasets\UpdateCommunityDatasetsWebController;
use App\Http\Controllers\Web\Communities\DeleteCommunityWebController;
use App\Http\Controllers\Web\Communities\DestroyCommunityWebController;
use App\Http\Controllers\Web\Communities\EditCommunityDatasetsWebController;
use App\Http\Controllers\Web\Communities\EditCommunityFilesWebController;
use App\Http\Controllers\Web\Communities\EditCommunityLinksWebController;
use App\Http\Controllers\Web\Communities\EditCommunityWebController;
use App\Http\Controllers\Web\Communities\Files\DeleteFileForCommunityWebController;
use App\Http\Controllers\Web\Communities\Files\DestroyFileForCommunityWebController;
use App\Http\Controllers\Web\Communities\Files\DisplayCommunityFileWebController;
use App\Http\Controllers\Web\Communities\Files\DownloadCommunityFileWebController;
use App\Http\Controllers\Web\Communities\Files\ShowCommunityFileWebController;
use App\Http\Controllers\Web\Communities\Files\StoreFilesForCommunityWebController;
use App\Http\Controllers\Web\Communities\Files\UploadFilesToCommunityWebController;
use App\Http\Controllers\Web\Communities\IndexCommunitiesWebController;
use App\Http\Controllers\Web\Communities\ShowCommunityRecommendedPracticesWebController;
use App\Http\Controllers\Web\Communities\ShowCommunityWebController;
use App\Http\Controllers\Web\Communities\StoreCommunityWebController;
use App\Http\Controllers\Web\Communities\UpdateCommunityWebController;
use Illuminate\Support\Facades\Route;

Route::get('/communities', IndexCommunitiesWebController::class)
     ->name('communities.index');

Route::get('/communities/create', CreateCommunityWebController::class)
     ->name('communities.create');

Route::post('/communities', StoreCommunityWebController::class)
     ->name('communities.store');

Route::get('/communities/{community}', ShowCommunityWebController::class)
     ->name('communities.show');

Route::get('/communities/{community}/practices', ShowCommunityRecommendedPracticesWebController::class)
     ->name('communities.practices.show');

Route::get('/communities/{community}/edit', EditCommunityWebController::class)
     ->name('communities.edit');

Route::get('/communities/{community}/edit/files', EditCommunityFilesWebController::class)
     ->name('communities.files.edit');

Route::get('/communities/{community}/files/{file}/download', DownloadCommunityFileWebController::class)
     ->name('communities.files.download');

Route::get('/communities/{community}/files/{file}/display', DisplayCommunityFileWebController::class)
     ->name('communities.files.display');

Route::get('/communities/{community}/files/{file}/delete', DeleteFileForCommunityWebController::class)
     ->name('communities.files.delete');

Route::delete('/communities/{community}/files/{file}/destroy', DestroyFileForCommunityWebController::class)
     ->name('communities.files.destroy');

Route::get('/communities/{community}/files/{file}/show', ShowCommunityFileWebController::class)
     ->name('communities.files.show');

Route::get('/communities/{community}/edit/links', EditCommunityLinksWebController::class)
     ->name('communities.links.edit');

Route::get('/communities/{community}/update/datasets', UpdateCommunityDatasetsWebController::class)
     ->name('communities.datasets.update');

Route::put('/communities/{community}', UpdateCommunityWebController::class)
     ->name('communities.update');

Route::get('/communities/{community}/delete', DeleteCommunityWebController::class)
     ->name('communities.delete');

Route::delete('/communities/{community}', DestroyCommunityWebController::class)
     ->name('communities.destroy');

Route::get('/communities/{community}/files/upload', UploadFilesToCommunityWebController::class)
     ->name('communities.files.upload');

Route::post('/communities/{community}/files/upload/store', StoreFilesForCommunityWebController::class)
     ->name('communities.files.upload.store');



