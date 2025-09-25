@extends('layouts.app')

@section('pageTitle', 'Google Sheets Integration')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Google Sheets Integration</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h4>Connection Status</h4>
                    <p>
                        @if ($isConnected)
                            <span class="text-success">✓ Connected to Google Sheets</span>
                            <a href="{{ route('google-sheets.authorize') }}" class="btn btn-sm btn-outline-primary ml-2">Reconnect</a>
                        @else
                            <span class="text-danger">✗ Not connected to Google Sheets</span>
                            <a href="{{ route('google-sheets.authorize') }}" class="btn btn-primary ml-2">Connect to Google Sheets</a>
                        @endif
                    </p>

                    @if ($isConnected)
                        <hr>
                        <h4>Spreadsheet Settings</h4>
                        <form action="{{ route('google-sheets.store-spreadsheet') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="form-group">
                                <label for="spreadsheet_id">Spreadsheet ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="spreadsheet_id" name="spreadsheet_id"
                                           value="{{ $spreadsheetId }}" required
                                           placeholder="Enter the ID from your Google Sheet URL">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    The Spreadsheet ID is found in the URL of your Google Sheet:
                                    https://docs.google.com/spreadsheets/d/<strong>spreadsheet_id</strong>/edit
                                </small>
                            </div>
                        </form>

                        @if ($spreadsheetId)
                            <hr>
                            <h4>Update Cell</h4>
                            <div class="mb-4">
                                <div class="form-group">
                                    <label for="cell">Cell Reference</label>
                                    <input type="text" class="form-control" id="cell" placeholder="e.g., A1, B2, etc." required>
                                </div>
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control" id="value" placeholder="Enter cell value" required>
                                </div>
                                <button type="button" id="update-cell-btn" class="btn btn-primary">Update Cell</button>
                                <div id="update-result" class="mt-2"></div>
                            </div>
                        @endif
                    @endif

                    <hr>
                    <h4>Instructions</h4>
                    <ol>
                        <li>Connect your account to Google Sheets using the button above.</li>
                        <li>Enter the ID of the Google Sheet you want to work with.</li>
                        <li>Use the form to update cells in your spreadsheet.</li>
                    </ol>
                    <p class="text-muted">
                        Note: Make sure your Google Sheet is accessible to your Google account.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateCellBtn = document.getElementById('update-cell-btn');
        if (updateCellBtn) {
            updateCellBtn.addEventListener('click', function() {
                const cell = document.getElementById('cell').value;
                const value = document.getElementById('value').value;
                const resultDiv = document.getElementById('update-result');

                if (!cell || !value) {
                    resultDiv.innerHTML = '<div class="alert alert-danger">Please fill in both cell reference and value.</div>';
                    return;
                }

                resultDiv.innerHTML = '<div class="alert alert-info">Updating cell...</div>';

                fetch('{{ route("google-sheets.update-cell") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cell: cell,
                        value: value,
                        spreadsheet_id: '{{ $spreadsheetId }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    } else {
                        resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = '<div class="alert alert-danger">An error occurred while updating the cell.</div>';
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endpush
