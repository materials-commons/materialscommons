<?php

namespace App\Http\Controllers\Web\GoogleSheets;

use App\Http\Controllers\Controller;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class IndexGoogleSheetsWebController extends Controller
{
    private $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $isConnected = $user->hasGoogleToken();
        $spreadsheetId =  "1quk-doIsfW8NjapsOGE0TXVZ6l530Uui1QXjx-KYOgI";//$user->google_spreadsheet_id;

        return view('app.google-sheets.index', [
            'isConnected' => $isConnected,
            'spreadsheetId' => $spreadsheetId,
        ]);
    }
}
