<div>
    <h3 class="mb-3">API Tokens</h3>

    @if (session()->has('api-token-message'))
        <div class="alert alert-info">
            {{ session('api-token-message') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            Default API Token
        </div>

        <div class="card-body">
            <p class="text-muted">
                This is your default API token. For security, tokens are only shown immediately after they are generated
                or regenerated.
            </p>

            @if ($plainDefaultToken)
                <div class="alert alert-warning">
                    <strong>Copy this token now.</strong>
                    It will not be shown again after you leave or refresh this page.
                </div>

                <div class="mb-3">
                    <label for="default-api-token" class="form-label">Default API Token</label>
                    <div class="input-group">
                        <input
                            id="default-api-token"
                            type="text"
                            class="form-control font-monospace"
                            value="{{ $plainDefaultToken }}"
                            readonly
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="navigator.clipboard.writeText(document.getElementById('default-api-token').value)"
                        >
                            Copy
                        </button>
                    </div>
                </div>
            @endif

            @if (blank($user->api_token))
                <button
                    type="button"
                    class="btn btn-primary"
                    wire:click="generateDefaultToken"
                    wire:loading.attr="disabled"
                >
                    Generate Default API Token
                </button>
            @else
                <button
                    type="button"
                    class="btn btn-outline-danger"
                    wire:click="regenerateDefaultToken"
                    wire:loading.attr="disabled"
                    wire:confirm="Regenerating this token will invalidate clients using the current default token. Continue?"
                >
                    Regenerate Default API Token
                </button>

                <span class="text-muted ms-2">
                    This will invalidate clients using the current default token.
                </span>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Additional API Tokens
        </div>

        <div class="card-body">
            <p class="text-muted">
                Create named tokens for individual integrations or applications. Each token is shown only once.
            </p>

            <form wire:submit.prevent="createAdditionalToken" class="mb-4">
                <div class="row g-2 align-items-start">
                    <div class="col-md-8">
                        <label for="new-token-name" class="form-label">Token Name</label>
                        <input
                            id="new-token-name"
                            type="text"
                            class="form-control @error('newTokenName') is-invalid @enderror"
                            wire:model="newTokenName"
                            placeholder="Example: Google Sheets, desktop client, lab integration"
                        >

                        @error('newTokenName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-4 pt-md-4">
                        <button
                            type="submit"
                            class="btn btn-primary mt-md-2"
                            wire:loading.attr="disabled"
                        >
                            Add Token
                        </button>
                    </div>
                </div>
            </form>

            @if ($additionalTokens->isEmpty())
                <p class="text-muted mb-0">
                    You do not have any additional API tokens.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($additionalTokens as $apiToken)
                            <tr>
                                <td>
                                    {{ $apiToken->name }}

                                    @if (isset($plainAdditionalTokens[$apiToken->id]))
                                        <div class="alert alert-warning mt-2 mb-0">
                                            <strong>Copy this token now.</strong>
                                            It will not be shown again after you leave or refresh this page.

                                            <div class="input-group mt-2">
                                                <input
                                                    id="additional-api-token-{{ $apiToken->id }}"
                                                    type="text"
                                                    class="form-control font-monospace"
                                                    value="{{ $plainAdditionalTokens[$apiToken->id] }}"
                                                    readonly
                                                >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="navigator.clipboard.writeText(document.getElementById('additional-api-token-{{ $apiToken->id }}').value)"
                                                >
                                                    Copy
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td>{{ optional($apiToken->created_at)->toDayDateTimeString() }}</td>
                                <td>{{ optional($apiToken->updated_at)->toDayDateTimeString() }}</td>

                                <td class="text-end">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        wire:click="regenerateAdditionalToken({{ $apiToken->id }})"
                                        wire:loading.attr="disabled"
                                        wire:confirm="Regenerating this token will invalidate clients using the current token. Continue?"
                                    >
                                        Regenerate
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary ms-1"
                                        wire:click="deleteAdditionalToken({{ $apiToken->id }})"
                                        wire:loading.attr="disabled"
                                        wire:confirm="Delete this token? Clients using it will no longer be able to authenticate."
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
