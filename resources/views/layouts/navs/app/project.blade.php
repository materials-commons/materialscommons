<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('dashboard')}}"
                   data-toggle="tooltip"
                   title="Goto to the dashboard listing all your projects, and published datasets."
                   href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('projects.show')}}"
                   data-toggle="tooltip" title="View details about your project."
                   href="{{route('projects.show', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-vector-square mr-2"></i>
                    {{$project->name}}
                </a>
            </li>

            <li class="nav-item">
                <span class="ml-5">Data</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByOneOf(['projects.folders', 'projects.files'])}}"
                   data-toggle="tooltip" title="Access your project files."
                   href="{{route('projects.folders.show', [$project, $project->rootDir])}}">
                    <i class="fa-fw fas fa-folder mr-2"></i>
                    Files
                </a>
            </li>

            @if($nav_trash_count > 0)
                <li class="nav-item ml-3">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.trashcan')}}"
                       data-toggle="tooltip" title="View your deleted files and empty the trashcan."
                       href="{{route('projects.trashcan.index', [$project])}}">
                        <i class="fa-fw fas fa-trash-restore mr-2"></i>
                        Trash
                    </a>
                </li>
            @else
                <li class="nav-item ml-3">
                    <a class="nav-link fs-11 ml-5 nav-disabled"
                       data-toggle="tooltip"
                       title="Deleted files go here where they will be scheduled for permanent deletion."
                       href="#">
                        <i class="fa-fw fas fa-trash mr-2"></i>
                        Trash
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.sheets.index')}}"
                   data-toggle="tooltip" title="View you Excel spreadsheets, CSV files and Google sheets."
                   href="{{route('projects.sheets.index', [$project])}}">
                    <i class="fa-fw fas fa-file-excel mr-2"></i>Sheets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.entities')}}"
                   data-toggle="tooltip" title="View the experimental processes and samples loaded into your project."
                   href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">
                    <i class="fa-fw fas fa-cubes mr-2 "></i>
                    Samples
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.computations.entities.index')}}"
                   data-toggle="tooltip"
                   title="View the computational activities and entities loaded into your project."
                   href="{{route('projects.computations.entities.index', ['project' => $project->id, 'category' => 'computational'])}}">
                    <i class="fa-fw fas fa-square-root-alt mr-2 "></i>
                    Computations
                </a>
            </li>

            <li class="nav-item mt-2">
                <span class="ml-5">Organization</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.experiments')}}"
                   data-toggle="tooltip"
                   title="Track your experiments and computational studies. Create new experiments and studies by loading your spreadsheet data."
                   href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-flask mr-2"></i>
                    Experiments
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.datasets')}}"
                   data-toggle="tooltip" title="Track and publish your research results."
                   href="{{route('projects.datasets.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-book mr-2"></i>
                    Datasets
                </a>
            </li>

            @if(isInBeta('charts'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.charts.create')}}"
                       href="{{route('projects.charts.create', [$project])}}">
                        <i class="fa-fw fas fa-chart-bar mr-2"></i> Charts
                    </a>
                </li>
            @endif

            <li class="nav-item mt-2">
                <span class="ml-5">Actions</span>
            </li>

            @if(isInBeta('datahq'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.datahq')}}"
                       data-toggle="tooltip" title="Explore, chart and query your data."
                       href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'samples', 'context' => 'project'])}}">
                        <i class="fa-fw fas fa-binoculars mr-2"></i>
                        Explore Data
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5"
                   data-toggle="tooltip" title="Create and publish a dataset."
                   href="{{route('projects.datasets.create', [$project])}}">
                    <i class="fa-fw fas fa-file-export mr-2"></i>
                    Publish Data
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.users')}}"
                   data-toggle="tooltip" title="Control who has access to your project."
                   href="{{route('projects.users.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-users-cog mr-2"></i>
                    Project Members
                </a>
            </li>

            <x-projects.show-old-globus-side-nav :project="$project" :user="auth()->user()"/>

            @if(isInBeta("globusng"))
                <x-projects.show-globus-side-nav :project="$project"/>
            @endif

            @if(isInBeta("globusng2"))
                <x-projects.show-globus2-side-nav :project="$project"/>
            @endif

            @if(isInBeta('run_scripts'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.runs')}}"
                       data-toggle="tooltip"
                       title="View job run status and results"
                       href="{{route('projects.runs.index', [$project])}}">
                        <i class="fa-fw fas fa-terminal mr-2 "></i>
                        Run Results
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.triggers')}}"
                       data-toggle="tooltip"
                       title="View and create triggers that run scripts for specified events"
                       href="{{route('projects.triggers.index', [$project])}}">
                        <i class="fa-fw fas fa-bolt mr-2 "></i>
                        Triggers
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('communities')}}"
                   data-toggle="tooltip" title="Create and manage your research communities."
                   href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-city mr-2"></i>
                    My Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}"
                   data-toggle="tooltip"
                   title="Access your account details, including changing your password, viewing/changing your API key and other information."
                   href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user mr-2"></i>
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11"
                   data-toggle="tooltip" title="Access the public published datasets site."
                   href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-globe mr-2"></i>
                    Public Data
                </a>
            </li>

        </ul>

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Saved Reports</span>--}}
        {{--            <a class="d-flex align-items-center text-muted" href="#">--}}
        {{--            </a>--}}
        {{--        </h6>--}}

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Saved Searches</span>--}}
        {{--        </h6>--}}

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Quick Access</span>--}}
        {{--        </h6>--}}
    </div>
</nav>

@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({delay: {'show': 500}});
        });

        document.addEventListener('livewire:navigating', () => {
            $('[data-toggle="tooltip"]').each(function () {
                $(this).tooltip('hide');
            });
        }, {once: true});
    </script>
@endpush
