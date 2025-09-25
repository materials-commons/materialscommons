<?php

namespace App\Http\Controllers\Web\GoogleSheets;

use App\Http\Controllers\Controller;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateCellWebController extends Controller
{
    private $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'cell' => 'required|string',
            'value' => 'required|string',
            'spreadsheet_id' => 'nullable|string',
        ]);

        $user = $request->user();

        if (!$user->hasGoogleToken()) {
            return response()->json([
                'success' => false,
                'message' => 'You need to connect to Google Sheets first.',
            ], 400);
        }

        $cell = $request->input('cell');
        $value = $request->input('value');
        $spreadsheetId = $request->input('spreadsheet_id') ?? $user->google_spreadsheet_id;

        if (!$spreadsheetId) {
            return response()->json([
                'success' => false,
                'message' => 'No spreadsheet ID provided or stored.',
            ], 400);
        }

        try {
            // Update the cell in the Google Sheet
            $success = $this->googleSheetsService->updateCell($user, $spreadsheetId, $cell, $value);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update cell in Google Sheet.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => "Cell {$cell} updated successfully!",
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update cell in Google Sheet: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update cell: ' . $e->getMessage(),
            ], 500);
        }
    }
}
