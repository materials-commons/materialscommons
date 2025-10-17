{{-- Actions Section --}}
<div class="sidebar-group">
    <div class="sidebar-group-header" id="project-sidenav-actions">
        <i class="fas fa-tools fa-sm me-2 mb-1 ms-1"></i>Actions
    </div>

    {{-- Data Management Actions --}}
    <div class="sidebar-subgroup">
        <div class="sidebar-subgroup-header">
            <i class="fas fa-database fa-sm me-2 mb-1 ms-2"></i>Data Management
        </div>
        <ul class="nav flex-column">
            @if(isInBeta('datahq'))
                <li class="nav-item ms-3">
                    <a class="nav-link fs-12 {{setActiveNavByName('projects.datahq')}}"
                       data-bs-toggle="tooltip" title="Explore, chart and query your data."
                       href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'samples', 'context' => 'project'])}}">
                        <i class="fa-fw fas fa-binoculars me-2 mb-1 fs-11"></i>Explore Data
                    </a>
                </li>
            @endif

            <li class="nav-item ms-3">
                <a class="nav-link fs-12"
                   data-bs-toggle="tooltip" title="Create and publish a dataset."
                   href="{{route('projects.datasets.create', [$project])}}">
                    <i class="fa-fw fas fa-file-export me-2 mb-1 fs-11"></i>Publish Data
                </a>
            </li>
        </ul>
    </div>

    {{-- File Transfer --}}
    <div class="sidebar-subgroup">
        <div class="sidebar-subgroup-header">
            <i class="fas fa-exchange-alt fa-sm me-2 mb-1 ms-2"></i>File Transfer
        </div>
        <ul class="nav flex-column">
            <li class="nav-item ms-3">
                <a href="{{route('projects.upload-files', [$project])}}"
                   class="nav-link fs-12">
                    <i class="fas fa-upload me-2 mb-1 fs-11"></i> Start Web Upload
                </a>
            </li>
            <x-projects.show-old-globus-side-nav :project="$project" :user="auth()->user()"/>

            {{--            @if(isInBeta("globusng"))--}}
            {{--                <x-projects.show-globus-side-nav :project="$project"/>--}}
            {{--            @endif--}}

            @if(isInBeta("globusng2"))
                <x-projects.show-globus2-side-nav :project="$project"/>
            @endif
        </ul>
    </div>

    {{-- Project Management --}}
    <div class="sidebar-subgroup">
        <div class="sidebar-subgroup-header">
            <i class="fas fa-cogs fa-sm me-2 mb-1 ms-2"></i>Project Management
        </div>
        <ul class="nav flex-column">
            <li class="nav-item ms-3">
                <a class="nav-link fs-12 {{setActiveNavByName('projects.users')}}"
                   data-bs-toggle="tooltip" title="Control who has access to your project."
                   href="{{route('projects.users.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-users-cog me-2 mb-1 fs-11"></i>Project Members
                </a>
            </li>
        </ul>
    </div>

    @if(isInBeta('run_scripts'))
        <div class="sidebar-subgroup">
            <div class="sidebar-subgroup-header">
                <i class="fas fa-robot fa-sm me-2 mb-1 ms-2"></i>Automation
            </div>
            <ul class="nav flex-column">
                <li class="nav-item ms-3">
                    <a class="nav-link fs-12 {{setActiveNavByName('projects.runs')}}"
                       data-bs-toggle="tooltip" title="View job run status and results"
                       href="{{route('projects.runs.index', [$project])}}">
                        <i class="fa-fw fas fa-terminal me-2 mb-1 fs-11"></i>Run Results
                    </a>
                </li>

                {{--                    <li class="nav-item">--}}
                {{--                        <a class="nav-link fs-11 {{setActiveNavByName('projects.triggers')}}"--}}
                {{--                           data-bs-toggle="tooltip" title="View and create triggers that run scripts for specified events"--}}
                {{--                           href="{{route('projects.triggers.index', [$project])}}">--}}
                {{--                            <i class="fa-fw fas fa-bolt me-2"></i>Triggers--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
            </ul>
        </div>
    @endif
</div>
