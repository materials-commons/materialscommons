@php
    $navbarSearchText = "Search published data...";
    if (Request::route('project')) {
        $navbarSearchText = "Search this project...";
    } elseif (auth()->check() && !Request::routeIs('public.*', 'public.index', 'search.public', 'datasets.show-by-doi')) {
        $navbarSearchText = "Search across projects...";
    }
@endphp

<div class="flex-grow-1 px-3">
    <button type="button"
            id="global-search-button"
            class="form-control form-rounded-search bg-white text-start d-flex align-items-center justify-content-between"
            style="height: 38px;"
            data-bs-toggle="modal"
            data-bs-target="#global-search-modal"
            aria-label="Open search">
        <span class="text-muted">
            <i class="fas fa-search me-1"></i>
            {{$navbarSearchText}}
        </span>
        <span class="text-muted small d-none d-lg-inline">
            Ctrl K
        </span>
    </button>
</div>
