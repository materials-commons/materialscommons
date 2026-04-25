{{--
  Prototype v2 tabs - improved navigation with icons, tooltips for long labels,
  and a prominent "Create Project" CTA button.
  Replace tabs.blade.php to try this out.
--}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <ul class="nav nav-pills flex-wrap gap-1" role="tablist">
        <li class="nav-item" id="dashboard-projects-tab">
            <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.show')}}"
               href="{{route('dashboard.projects.show')}}"
               aria-current="{{ Request::routeIs('dashboard.projects.show') ? 'page' : 'false' }}">
                <i class="fas fa-folder-open me-1"></i>
                Projects
                <span class="badge rounded-pill ms-1
                    {{ Request::routeIs('dashboard.projects.show') ? 'bg-white text-primary' : 'bg-primary text-white' }}">
                    {{$projectsCount}}
                </span>
            </a>
        </li>

        <li class="nav-item" id="dashboard-published-datasets-tab">
            <a class="nav-link no-underline {{setActiveNavByName('dashboard.published-datasets.show')}}"
               href="{{route('dashboard.published-datasets.show')}}"
               aria-current="{{ Request::routeIs('dashboard.published-datasets.show') ? 'page' : 'false' }}">
                <i class="fas fa-database me-1"></i>
                Published Datasets
                <span class="badge rounded-pill ms-1
                    {{ Request::routeIs('dashboard.published-datasets.show') ? 'bg-white text-primary' : 'bg-primary text-white' }}">
                    {{$publishedDatasetsCount}}
                </span>
            </a>
        </li>

        <li class="nav-item" id="dashboard-archived-projects-tab">
            <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.archived.index')}}"
               href="{{route('dashboard.projects.archived.index')}}"
               aria-current="{{ Request::routeIs('dashboard.projects.archived.index') ? 'page' : 'false' }}">
                <i class="fas fa-archive me-1"></i>
                Archived
                <span class="badge rounded-pill ms-1
                    {{ Request::routeIs('dashboard.projects.archived.index') ? 'bg-white text-primary' : 'bg-secondary text-white' }}">
                    {{$archivedCount}}
                </span>
            </a>
        </li>

        @if($deletedCount > 0)
        <li class="nav-item" id="dashboard-projects-trash-tab">
            <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.trash.index')}}"
               href="{{route('dashboard.projects.trash.index')}}"
               data-bs-toggle="tooltip"
               title="Projects scheduled for permanent deletion"
               aria-current="{{ Request::routeIs('dashboard.projects.trash.index') ? 'page' : 'false' }}">
                <i class="fas fa-trash-alt me-1"></i>
                Trash
                <span class="badge rounded-pill bg-danger text-white ms-1">{{$deletedCount}}</span>
            </a>
        </li>
        @endif
    </ul>

    {{-- Prominent CTA always visible in the tab bar --}}
    <a href="{{route('projects.create')}}" class="btn btn-primary btn-sm flex-shrink-0">
        <i class="fas fa-plus me-1"></i> New Project
    </a>
</div>
