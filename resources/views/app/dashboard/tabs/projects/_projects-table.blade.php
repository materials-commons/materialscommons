@php
    $activeProjectIds = collect($activeProjects)->pluck('id')->flip()->all();
    $recentProjectIds = collect($recentlyAccessedProjects)->pluck('id')->flip()->all();
    $starredCount     = collect($projects)->filter(fn($p) => isset($activeProjectIds[$p->id]))->count();
    $recentCount      = collect($projects)->filter(fn($p) => isset($recentProjectIds[$p->id]))->count();
@endphp

{{-- Filter tabs --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="btn-group btn-group-sm" role="group" aria-label="Project filter" id="project-filters">
        <button type="button" class="btn btn-outline-secondary active" data-filter="all">
            All
            <span class="badge ms-1 text-bg-secondary">{{ count($projects) }}</span>
        </button>
        <button type="button" class="btn btn-outline-secondary" data-filter="starred">
            <i class="fas fa-star text-warning me-1"></i>Starred
            <span class="badge ms-1 text-bg-secondary">{{ $starredCount }}</span>
        </button>
        <button type="button" class="btn btn-outline-secondary" data-filter="recent">
            <i class="fas fa-clock me-1"></i>Recent
            <span class="badge ms-1 text-bg-secondary">{{ $recentCount }}</span>
        </button>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus me-1"></i> New Project
    </a>
</div>

<table id="projects" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Project</th>
        <th>Files</th>
        <th>Published Datasets</th>
        <th>Owner</th>
        <th>Updated</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($projects as $proj)
        @php
            $isStarred = isset($activeProjectIds[$proj->id]);
            $isRecent  = isset($recentProjectIds[$proj->id]);
        @endphp
        <tr data-starred="{{ $isStarred ? '1' : '0' }}"
            data-recent="{{ $isRecent ? '1' : '0' }}">
            <td>
                <a href="{{ route('projects.show', [$proj->id]) }}" class="no-underline">
                    <x-health.projects.health-status-badge-small :project="$proj"/>
                    {{ $proj->name }}
                </a>
                @if($isStarred)
                    <i class="fas fa-star text-warning ms-1" style="font-size:.7rem;"
                       title="Starred project"></i>
                @elseif($isRecent)
                    <i class="fas fa-clock text-muted ms-1" style="font-size:.7rem;"
                       title="Recently accessed"></i>
                @endif
            </td>
            <td>{{ number_format($proj->file_count) }}</td>
            <td>{{ number_format($proj->published_datasets_count) }}</td>
            <td>{{ $proj->owner->name }}</td>
            <td>{{ $proj->updated_at->format('M j, Y') }}</td>
            <td>
                <div class="float-end d-flex align-items-center gap-2">
                    @if($isStarred)
                        <a href="{{ route('dashboard.projects.unmark-as-active', [$proj]) }}"
                           class="action-link text-warning" title="Remove from starred">
                            <i class="fas fa-fw fa-star"></i>
                        </a>
                    @else
                        <a href="{{ route('dashboard.projects.mark-as-active', [$proj]) }}"
                           class="action-link text-muted" title="Star this project">
                            <i class="far fa-fw fa-star"></i>
                        </a>
                    @endif
                    @if(auth()->id() == $proj->owner_id)
                        <a href="{{ route('dashboard.projects.archive', [$proj]) }}"
                           class="action-link text-muted"
                           data-bs-toggle="tooltip"
                           title="Archive project">
                            <i class="fas fa-fw fa-archive"></i>
                        </a>
                        <a data-bs-toggle="modal" href="#project-delete-{{ $proj->id }}"
                           class="action-link text-muted">
                            <i class="fas fa-fw fa-trash-alt"></i>
                        </a>
                        @component('app.projects.delete-project', ['project' => $proj])
                        @endcomponent
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        let projectsCount = "{{ sizeof($projects) }}";

        $(document).ready(() => {
            if (projectsCount === "0") {
                $('#welcome-dialog').modal();
            }

            // Active filter state
            let activeFilter = localStorage.getItem('mc_projects_filter') || 'all';

            // Custom DataTable search — runs before the user's text search
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                if (settings.nTable.id !== 'projects') return true;
                if (activeFilter === 'all') return true;
                const row = settings.aoData[dataIndex].nTr;
                if (!row) return true;
                if (activeFilter === 'starred') return row.dataset.starred === '1';
                if (activeFilter === 'recent')  return row.dataset.recent  === '1';
                return true;
            });

            const table = $('#projects').DataTable({
                stateSave: true,
                pageLength: 100,
                order: [[1, 'desc']],   // default: sort by name
            });

            // Restore saved filter button state
            const $buttons = $('#project-filters button');
            $buttons.filter(`[data-filter="${activeFilter}"]`).addClass('active')
                    .siblings().removeClass('active');
            table.draw();

            // Filter button clicks
            $buttons.on('click', function () {
                activeFilter = $(this).data('filter');
                localStorage.setItem('mc_projects_filter', activeFilter);
                $buttons.removeClass('active');
                $(this).addClass('active');
                table.draw();
            });
        });
    </script>
@endpush
