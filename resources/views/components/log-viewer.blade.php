<div>
    @include('components.partials._set-log-level-modal')
    <x-card>
        <x-slot name="header">
            @if(!is_null($setLogLevelRoute))
                <a class="action-link float-right ms-4"
                   data-bs-toggle="modal" href="#set-log-level-modal">
                    <i class="fas fa-fw fa-edit me-2"></i>Set Log Level
                </a>
            @endif

            <a class="action-link float-right" style="cursor: pointer"
               hx-get="{{$loadLogRoute}}"
               onclick="clearLogSearchInputOnReload()"
               hx-target="#etl-log"
               hx-swap="innerHTML scroll:bottom">
                <i class="fas fa-fw fa-sync-alt me-2"></i>Refresh Log
            </a>
        </x-slot>
        <x-slot name="body">
            <h3>
                Search Log
                <span class="htmx-indicator">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </h3>

            <input class="form-control col-6 mb-4" type="text" id="search-input"
                   name="search" placeholder="Search log..."
                   hx-get="{{$searchLogRoute}}"
                   hx-target="#etl-log"
                   hx-indicator=".htmx-indicator"
                   hx-trigger="keyup changed delay:500ms">
            <div hx-get="{{$loadLogRoute}}"
                 hx-trigger="load" hx-target="#etl-log">
                <div id="etl-log" style="overflow-y: auto; overflow-x: auto; height: 70vh"></div>
            </div>
        </x-slot>
    </x-card>
    @push('scripts')
        <script>
            if (typeof clearLogSearchInputOnReload === 'undefined') {
                function clearLogSearchInputOnReload() {
                    document.getElementById('search-input').value = '';
                }
            }
        </script>
    @endpush
</div>
