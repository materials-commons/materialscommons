<div class="container-fluid">
    {{-- Results Section --}}
    <div class="card mb-3">
        <div class="card-body inner-card" style="max-height: 400px; overflow-y: auto;">
            <h5 class="card-title mb-3">Query Results</h5>
            <hr/>
            @forelse($results as $index => $result)
                <div class="mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <code class="text-primary">{{ $result['query'] }}</code>
                        <small class="text-muted">{{ $result['timestamp'] }}</small>
                    </div>
                    @if(isset($result['error']))
                        <div class="alert alert-danger mb-0">{{ $result['error'] }}</div>
                    @else
                        <pre class="mb-0">{{ json_encode($result['data'], JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0">No results yet. Execute a query below.</p>
            @endforelse
        </div>
    </div>

    <div class="mb-3">
                <textarea
                    wire:model="query"
                    class="form-control font-monospace"
                    rows="6"
                    placeholder="Enter your query here... (Ctrl+Enter to execute)"
                    x-data
                    @keydown.ctrl.enter="$wire.executeQuery()"></textarea>
    </div>
    <button
        wire:click="executeQuery"
        class="btn btn-primary"
        wire:loading.attr="disabled">
        <span wire:loading.remove>Execute Query</span>
        <span wire:loading>
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Executing...
                </span>
    </button>

    {{-- Query Input Section --}}
{{--    <div class="card">--}}
{{--        <div class="card-header">--}}
{{--            <h5 class="mb-0">Query Editor</h5>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div class="mb-3">--}}
{{--                <textarea--}}
{{--                    wire:model="query"--}}
{{--                    class="form-control font-monospace"--}}
{{--                    rows="6"--}}
{{--                    placeholder="Enter your query here... (Ctrl+Enter to execute)"--}}
{{--                    x-data--}}
{{--                    @keydown.ctrl.enter="$wire.executeQuery()"--}}
{{--                ></textarea>--}}
{{--            </div>--}}
{{--            <button--}}
{{--                wire:click="executeQuery"--}}
{{--                class="btn btn-primary"--}}
{{--                wire:loading.attr="disabled"--}}
{{--            >--}}
{{--                <span wire:loading.remove>Execute Query</span>--}}
{{--                <span wire:loading>--}}
{{--                    <span class="spinner-border spinner-border-sm me-1"></span>--}}
{{--                    Executing...--}}
{{--                </span>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
