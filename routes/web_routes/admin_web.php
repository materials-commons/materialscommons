<?php

use App\Http\Controllers\Web\Admin\MCFS\CreateTransferRequestWebController;
use App\Http\Controllers\Web\Admin\MCFS\SearchMCFSLogWebController;
use App\Http\Controllers\Web\Admin\MCFS\SetMCFSLogLevelWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowMCFSLogViewerWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowMCFSLogWebController;
use App\Http\Controllers\Web\Admin\MCFS\ShowTransferRequestWebController;
use App\Http\Controllers\Web\Admin\ShowAdminDashboardWebController;
use App\Http\Controllers\Web\Admin\MCFS\IndexMCFSWebController;
use Illuminate\Support\Facades\Route;


Route::get('/admin/dashboard', ShowAdminDashboardWebController::class)
     ->name('admin.dashboard');

Route::get('/admin/dashboard/mcfs/transfer-requests/{transferRequest}', ShowTransferRequestWebController::class)
     ->name('admin.dashboard.mcfs.transfer-requests.show');

Route::post('/admin/dashboard/mcfs/transfer-requests', CreateTransferRequestWebController::class)
     ->name('admin.dashboard.mcfs.transfer-requests.create');

Route::get('/admin/dashboard/mcfs/log', ShowMCFSLogWebController::class)
     ->name('admin.dashboard.mcfs.log.show');

Route::post('/admin/dashboard/mcfs/log/update', SetMCFSLogLevelWebController::class)
     ->name('admin.dashboard.mcfs.log.set-log-level');

Route::get('/admin/dashboard/mcfs/log/search', SearchMCFSLogWebController::class)
     ->name('admin.dashboard.mcfs.log.search');

Route::get('/dashboard/admin/mcfs/index', IndexMCFSWebController::class)
     ->name('admin.dashboard.mcfs.index');

Route::get('/dashboard/admin/mcfs/show-log-viewer', ShowMCFSLogViewerWebController::class)
     ->name('admin.dashboard.mcfs.show-log-viewer');

Route::get('/dashboard/admin/mcfs/show-log', ShowMCFSLogWebController::class)
     ->name('admin.dashboard.mcfs.show-log');

Route::get('/dashboard/admin/mcfs/search-log', SearchMCFSLogWebController::class)
     ->name('admin.dashboard.mcfs.search-log');

