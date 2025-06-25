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
    <div id="search-results"
         style="position:absolute; z-index:999; overflow-y: auto; max-height: 70vh;width:100%;left:0;top:100%;display:none;"></div>
</div>

@push('scripts')
    <script>
        if (typeof closeSearch !== 'function') {
            function closeSearch() {
                console.log('closeSearch');
                $('#navbar-search-input').val('');
                document.getElementById('search-results').style.display = 'none';
            }
        }

        // A handler for escape that checks if there are search results. If there
        // are search results, then have javascript simulate a user click on the
        // search results close to make the search results go away.
        document.addEventListener('keyup', function (event) {
            if (event.key === 'Escape') {
                const searchResults = document.getElementById('search-results');
                // Only close if search results exist and have content
                if (searchResults && searchResults.innerHTML.trim() !== '') {
                    document.getElementById('search-close').click();
                }
            }
        });

        document.addEventListener('htmx:afterOnLoad', function (evt) {
            console.log('showSearchResults');
            if (evt.detail.target.id === 'search-results') {
                document.getElementById('search-results').style.display = '';
            }
        });

    </script>
@endpush