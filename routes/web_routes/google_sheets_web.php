<?php

use App\Http\Controllers\Web\GoogleSheets\AuthorizeGoogleSheetsWebController;
use App\Http\Controllers\Web\GoogleSheets\CallbackGoogleSheetsWebController;
use App\Http\Controllers\Web\GoogleSheets\IndexGoogleSheetsWebController;
use App\Http\Controllers\Web\GoogleSheets\StoreSpreadsheetIdWebController;
use App\Http\Controllers\Web\GoogleSheets\UpdateCellWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('google-sheets')->name('google-sheets.')->group(function () {
    // Index page for Google Sheets integration
    Route::get('/', IndexGoogleSheetsWebController::class)->name('index');

    // OAuth routes
    Route::get('/authorize', AuthorizeGoogleSheetsWebController::class)->name('authorize');
    Route::get('/callback', CallbackGoogleSheetsWebController::class)->name('callback');

    // Spreadsheet management
    Route::post('/spreadsheet', StoreSpreadsheetIdWebController::class)->name('store-spreadsheet');

    // Cell operations
    Route::post('/update-cell', UpdateCellWebController::class)->name('update-cell');
});
