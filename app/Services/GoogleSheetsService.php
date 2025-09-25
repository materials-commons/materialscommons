<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    private $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);
    }

    /**
     * Get the authorization URL for Google OAuth.
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle the OAuth callback and store the token.
     *
     * @param string $code
     * @param User $user
     * @return User
     */
    public function handleCallback($code, User $user)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($accessToken['error'])) {
            Log::error('Google OAuth error: ' . $accessToken['error']);
            throw new \Exception('Failed to get access token: ' . $accessToken['error']);
        }

        return $this->storeToken($accessToken, $user);
    }

    /**
     * Store the token in the database.
     *
     * @param array $accessToken
     * @param User $user
     * @return User
     */
    public function storeToken($accessToken, User $user)
    {
        // Calculate expiration time
        $expiresAt = Carbon::now()->addSeconds($accessToken['expires_in'] ?? 3600);

        // Update the user with token information
        $user->update([
            'google_access_token' => $accessToken['access_token'],
            'google_refresh_token' => $accessToken['refresh_token'] ?? $user->google_refresh_token,
            'google_token_type' => $accessToken['token_type'] ?? 'Bearer',
            'google_expires_at' => $expiresAt,
        ]);

        return $user;
    }

    /**
     * Set the access token for the Google client.
     *
     * @param User $user
     * @return bool
     */
    public function setAccessToken(User $user)
    {
        if (!$user->hasGoogleToken()) {
            Log::error('User does not have a Google token');
            return false;
        }

        $accessToken = [
            'access_token' => $user->google_access_token,
            'refresh_token' => $user->google_refresh_token,
            'token_type' => $user->google_token_type,
            'expires_in' => $user->google_expires_at ? Carbon::now()->diffInSeconds($user->google_expires_at) : 0,
        ];

        $this->client->setAccessToken($accessToken);

        // Check if token is expired and refresh if needed
        if ($this->client->isAccessTokenExpired()) {
            return $this->refreshToken($user);
        }

        return true;
    }

    /**
     * Refresh the access token.
     *
     * @param User $user
     * @return bool
     */
    public function refreshToken(User $user)
    {
        if (empty($user->google_refresh_token)) {
            Log::error('Cannot refresh token: No refresh token available');
            return false;
        }

        try {
            $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $accessToken = $this->client->getAccessToken();

            // Update token in database
            $expiresAt = Carbon::now()->addSeconds($accessToken['expires_in'] ?? 3600);

            $user->update([
                'google_access_token' => $accessToken['access_token'],
                'google_refresh_token' => $accessToken['refresh_token'] ?? $user->google_refresh_token,
                'google_token_type' => $accessToken['token_type'] ?? $user->google_token_type,
                'google_expires_at' => $expiresAt,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to refresh token: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get values from a Google Sheet.
     *
     * @param User $user
     * @param string $spreadsheetId
     * @param string $range
     * @return array|null
     */
    public function getValues(User $user, $spreadsheetId, $range)
    {
        if (!$this->setAccessToken($user)) {
            return null;
        }

        try {
            $service = new Sheets($this->client);
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (\Exception $e) {
            Log::error('Failed to get values from Google Sheet: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update values in a Google Sheet.
     *
     * @param User $user
     * @param string $spreadsheetId
     * @param string $range
     * @param array $values
     * @return bool
     */
    public function updateValues(User $user, $spreadsheetId, $range, $values)
    {
        if (!$this->setAccessToken($user)) {
            return false;
        }

        try {
            $service = new Sheets($this->client);

            $body = new ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED'
            ];

            $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update values in Google Sheet: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a single cell in a Google Sheet.
     *
     * @param User $user
     * @param string $spreadsheetId
     * @param string $cell
     * @param mixed $value
     * @return bool
     */
    public function updateCell(User $user, $spreadsheetId, $cell, $value)
    {
        return $this->updateValues($user, $spreadsheetId, $cell, [[$value]]);
    }

    /**
     * Store the spreadsheet ID for a user.
     *
     * @param User $user
     * @param string $spreadsheetId
     * @return User|null
     */
    public function storeSpreadsheetId(User $user, $spreadsheetId)
    {
        if (!$user->hasGoogleToken()) {
            return null;
        }

//        $user->update(['google_spreadsheet_id' => $spreadsheetId]);

        return $user;
    }


    public function getCellInfo($user, $spreadsheetId, $range)
    {
        if (!$this->setAccessToken($user)) {
            return null;
        }

        try {
            $service = new Sheets($this->client);

            // Get the raw formula/value using FORMULA render option
            $response = $service->spreadsheets_values->get(
                $spreadsheetId,
                $range,
                [
                    'valueRenderOption' => 'FORMULA' // This returns formulas as entered by user
                ]
            );

            $values = $response->getValues();
            if (!empty($values) && !empty($values[0])) {
                $cellValue = $values[0][0];

                return [
                    'isFormula' => is_string($cellValue) && str_starts_with($cellValue, '='),
                    'value' => $cellValue
                ];
            }

            return [
                'isFormula' => false,
                'value' => null
            ];

        } catch (\Exception $e) {
            \Log::error('Error getting cell info: ' . $e->getMessage());
            return null;
        }
    }
}
