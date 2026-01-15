<div class="container-fluid">
    {{-- Results Section --}}
    <div class="card mb-3">
        <div class="card-body inner-card"
             x-data
             x-ref="resultsContainer"
             @scroll-to-bottom.window="$nextTick(() => $refs.resultsContainer.scrollTop = $refs.resultsContainer.scrollHeight)"
             style="max-height: 600px; overflow-y: auto;">
            <h5 class="card-title mb-3">Query Results</h5>
            <hr/>
            @forelse($results as $index => $result)
                <div class="mb-4 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <code class="text-primary"
                              wire:click="loadQuery('{{ addslashes($result['query']) }}')"
                              title="Click to load this query into the query box below."
                              style="cursor:pointer">
                            {{ $result['query'] }}
                        </code>
                        <small class="text-muted">{{ $result['timestamp'] }}</small>
                    </div>
                    @if(isset($result['error']))
                        <div class="alert alert-danger mb-0">{{ $result['error'] }}</div>
                    @else
                        <pre class="mb-0 pb-3" stylex="overflow-x: auto; max-width: 100%;">{{ $result['data'] }}</pre>
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
    <button wire:click="executeQuery"
            class="btn btn-primary"
            wire:loading.attr="disabled">
        <span wire:loading.remove>Execute Query</span>
        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Executing...</span>
    </button>
    <button wire:click="executeAndClearQuery"
            class="btn btn-primary"
            wire:loading.attr="disabled">
        <span wire:loading.remove>Execute & Clear</span>
        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Executing...</span>
    </button>
    <button wire:click="clearResults" class="btn btn-danger">Clear Results</button>
</div>
