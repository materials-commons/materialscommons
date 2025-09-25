<?php

namespace App\Http\Controllers\Web\GoogleSheets;

use App\Http\Controllers\Controller;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class AuthorizeGoogleSheetsWebController extends Controller
{
    private $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function __invoke(Request $request)
    {
        // Get the authorization URL from the Google Sheets service
        $authUrl = $this->googleSheetsService->getAuthUrl();

        // Redirect the user to the Google OAuth consent screen
        return redirect($authUrl);
    }
}
