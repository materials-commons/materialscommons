<?php

namespace App\Http\Controllers\Web\GoogleSheets;

use App\Http\Controllers\Controller;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackGoogleSheetsWebController extends Controller
{
    private $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function __invoke(Request $request)
    {
        // Check if the user denied the authorization
        if ($request->has('error')) {
            Log::error('Google OAuth error: ' . $request->get('error'));
            return redirect()->route('google-sheets.index')
                ->with('error', 'Authorization failed: ' . $request->get('error'));
        }

        // Get the authorization code from the callback
        $code = $request->get('code');

        try {
            // Exchange the authorization code for an access token and store it
            $this->googleSheetsService->handleCallback($code, $request->user());

            return redirect()->route('google-sheets.index')
                ->with('success', 'Successfully connected to Google Sheets!');
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());

            return redirect()->route('google-sheets.index')
                ->with('error', 'Failed to connect to Google Sheets: ' . $e->getMessage());
        }
    }
}
