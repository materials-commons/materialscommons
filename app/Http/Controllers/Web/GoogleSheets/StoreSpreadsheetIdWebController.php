<?php

namespace App\Http\Controllers\Web\GoogleSheets;

use App\Http\Controllers\Controller;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreSpreadsheetIdWebController extends Controller
{
    private $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'spreadsheet_id' => 'required|string',
        ]);

        $user = $request->user();
        $spreadsheetId = $request->input('spreadsheet_id');

        try {
            // Store the spreadsheet ID for the user
            $token = $this->googleSheetsService->storeSpreadsheetId($user, $spreadsheetId);

            if (!$token) {
                return redirect()->route('google-sheets.index')
                    ->with('error', 'You need to connect to Google Sheets first.');
            }

            // Try to access the spreadsheet to verify it exists and is accessible
            $values = $this->googleSheetsService->getValues($token, $spreadsheetId, 'A1');

            if ($values === null) {
                return redirect()->route('google-sheets.index')
                    ->with('error', 'Could not access the spreadsheet. Please check the ID and make sure you have access to it.');
            }

            return redirect()->route('google-sheets.index')
                ->with('success', 'Spreadsheet ID saved successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to store spreadsheet ID: ' . $e->getMessage());

            return redirect()->route('google-sheets.index')
                ->with('error', 'Failed to save spreadsheet ID: ' . $e->getMessage());
        }
    }
}
