# Google Sheets Integration

This document provides information about the Google Sheets integration feature that allows users to update cells in Google Sheets from the application.

## Features

- OAuth authentication with Google
- Individual authentication tokens for each user
- Update cells in Google Sheets
- Simple UI for managing the integration

## Setup Instructions

### 1. Create a Google Cloud Project

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project
3. Enable the Google Sheets API
4. Configure the OAuth consent screen
5. Create OAuth 2.0 credentials (Client ID and Client Secret)

### 2. Configure Environment Variables

Add the following environment variables to your `.env` file:

```
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=https://your-app.com/app/google-sheets/callback
GOOGLE_PROJECT_ID=your_project_id
```

### 3. Run Database Migrations

Run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

## Usage

1. Navigate to `/app/google-sheets` in the application
2. Click "Connect to Google Sheets" to authorize the application
3. Enter the ID of your Google Sheet (found in the URL)
4. Use the form to update cells in your spreadsheet

## Technical Implementation

### Database Structure

The integration uses a `google_auth_tokens` table to store OAuth tokens for each user:

- `user_id`: Foreign key to the users table
- `access_token`: The OAuth access token
- `refresh_token`: The OAuth refresh token
- `token_type`: The token type (default: Bearer)
- `expires_at`: When the token expires
- `spreadsheet_id`: The ID of the Google Spreadsheet

### Components

1. **GoogleSheetsService**: Handles authentication and API interactions
2. **Controllers**:
   - `IndexGoogleSheetsWebController`: Displays the main page
   - `AuthorizeGoogleSheetsWebController`: Handles OAuth redirect
   - `CallbackGoogleSheetsWebController`: Handles OAuth callback
   - `StoreSpreadsheetIdWebController`: Stores the spreadsheet ID
   - `UpdateCellWebController`: Updates cells in the spreadsheet

3. **Views**:
   - `index.blade.php`: Main interface for the integration

## Testing

To test the integration:

1. Configure the Google Cloud project and environment variables
2. Navigate to `/app/google-sheets`
3. Connect your account to Google Sheets
4. Enter a valid spreadsheet ID
5. Try updating cells in the spreadsheet

## Troubleshooting

- **Authentication Errors**: Check that your Google Cloud project is properly configured and the redirect URI matches exactly.
- **Access Errors**: Ensure the Google account has access to the spreadsheet.
- **Token Refresh Issues**: If tokens aren't refreshing properly, try reconnecting to Google Sheets.

## Future Improvements

- Add support for reading data from Google Sheets
- Implement batch updates for multiple cells
- Add support for formatting cells
- Create a more advanced spreadsheet editor interface
