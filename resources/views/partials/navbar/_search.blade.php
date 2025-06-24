<span class="htmx-indicator help-color"><i class="fas fa-spinner fa-spin fs-14"></i></span>

@if(Request::routeIs('public.datasets.*') && isset($dataset))
    @php
        // Create a search dataset controller and route
        $searchRoute = route('public.datasets.search', [$dataset]);
        $placeholder = "Search dataset...";
    @endphp
@elseif(isset($project))
    @php
        $searchRoute = route('projects.search.htmx', [$project]);
        $placeholder = "Search project...";
    @endphp
@elseif (Request::routeIs('public.*'))
    @php
        $searchRoute = route('public.search');
        $placeholder = "Search published data...";
    @endphp
@else
    @php
        $searchRoute = route('projects.search_all');
        $placeholder = "Search across projects...";
    @endphp
@endif

<div style="width:100%; position:relative;">
    <input type="text"
           id="navbar-search-input"
           class="form-control form-rounded border border-right-0"
           placeholder="{{$placeholder}}"
           name="search"
           aria-label="Search"
           hx-get="{{$searchRoute}}"
           hx-target="#search-results"
           hx-indicator=".htmx-indicator"
           hx-trigger="keyup changed delay:500ms">
    <div id="search-results" style="position:absolute; z-index:999; overflow-y: auto; height: 70vh;width:100%;left:0;top:100%;"></div>
</div>

@push('scripts')
    <script>
        if (typeof closeSearch !== 'function') {
            function closeSearch() {
                $('#navbar-search-input').val('');
            }
        }
    </script>
@endpush