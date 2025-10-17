<nav class="col-md-2 col-sm-2 d-none d-md-block bg-sidebar sidebar" id="project-sidebar">
    <div class="sidebar-sticky">
        @include('layouts.navs.app._navigation-section')


        {{-- Project Context --}}
        <div class="sidebar-group">
            <div class="sidebar-group-header">
                <i class="fas fa-project-diagram fa-sm me-2"></i>Current Project
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link fs-11 project-name {{setActiveNavByName('projects.show')}}"
                       data-bs-toggle="tooltip"
                       title="View details about your project."
                       href="{{route('projects.show', ['project' => $project->id])}}">
                        {{$project->name}}
                        <x-health.projects.health-status-badge :project="$project"/>
{{--                        <span class="ms-2 badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Critical</span>--}}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Project Sections --}}
        <div class="project-sections">
            @include('layouts.navs.app._data-section')
            @include('layouts.navs.app._organization-section')
            @include('layouts.navs.app._actions-section')
        </div>
    </div>
</nav>
