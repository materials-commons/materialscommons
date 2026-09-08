<div class="mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div>
            <h3 class="mb-1">{{ $heading }}</h3>
            <div class="text-muted">{{ $subheading }}</div>
        </div>
    </div>

    <form wire:submit="search" class="mt-3">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-white">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text"
                   class="form-control"
                   placeholder="Search..."
                   wire:model="query"
                   autocomplete="off">
            <button type="submit" class="btn btn-primary">
                Search
            </button>
        </div>
    </form>

    @if(!blank($query))
        <div class="text-muted small mt-2">
            Showing results for <strong>{{ $query }}</strong>
        </div>
    @endif
</div>
