<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10x bg-whitex sidebar" id="project-sidebar"
     style="background-color: #f5f5f5">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-1 mb-3">
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('dashboard')}}"
                   data-toggle="tooltip"
                   title="Goto to the dashboard listing all your projects, and published datasets."
                   href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-bars me-2"></i>Current Project</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.show')}}"
                   data-toggle="tooltip" title="View details about your project."
                   href="{{route('projects.show', ['project' => $project->id])}}">
                    {{$project->name}}
                    {{--                    <span class="position-relative d-inline-block pe-3">--}}
                    {{--                            {{$project->name}}--}}
                    {{--                            <span class="position-absolute top-0 start-100 translate-middle bg-green-5 rounded-circle"--}}
                    {{--                                  style="width: .5rem; height: .5rem; transform: translate(-25%, -25%) !important; box-shadow: none; outline: none;"></span>--}}
                    {{--                        </span>--}}
                </a>
            </li>

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-database me-2"></i>Data</span>
                <span class="ms-4" id="project-sidenav-data">{{-- Offset for tour --}}</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByOneOf(['projects.folders', 'projects.files'])}}"
                   data-toggle="tooltip" title="Access your project files."
                   href="{{route('projects.folders.show', [$project, $project->rootDir])}}">
                    <i class="fa-fw fas fa-folder me-2"></i>
                    Files
                </a>
            </li>

            @if($nav_trash_count > 0)
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.trashcan')}}"
                       data-toggle="tooltip" title="View your deleted files and empty the trashcan."
                       href="{{route('projects.trashcan.index', [$project])}}">
                        <i class="fa-fw fas fa-trash-restore me-2"></i>
                        Trash({{number_format($nav_trash_count)}})
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 nav-disabled"
                       data-toggle="tooltip"
                       title="Deleted files go here where they will be scheduled for permanent deletion."
                       href="#">
                        <i class="fa-fw fas fa-trash me-2"></i>
                        Trash
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.sheets.index')}}"
                   data-toggle="tooltip" title="View you Excel spreadsheets, CSV files and Google sheets."
                   href="{{route('projects.sheets.index', [$project])}}">
                    <i class="fa-fw fas fa-file-excel me-2"></i>
                    Sheets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.entities')}}"
                   data-toggle="tooltip" title="View the experimental processes and samples loaded into your project."
                   href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">
                    <i class="fa-fw fas fa-cubes me-2"></i>
                    Samples
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.computations.entities.index')}}"
                   data-toggle="tooltip"
                   title="View the computational activities and entities loaded into your project."
                   href="{{route('projects.computations.entities.index', ['project' => $project->id, 'category' => 'computational'])}}">
                    <i class="fa-fw fas fa-square-root-alt me-2 "></i>
                    Computations
                </a>
            </li>

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-sitemap me-2"></i>Organization</span>
                <span class="ms-4" id="project-sidenav-organization">{{-- Offset for tour --}}</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.experiments')}}"
                   data-toggle="tooltip"
                   title="Track your experimental and computational studies. Create new studies by loading your spreadsheet data."
                   href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-flask me-2"></i>
                    Studies
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.datasets')}}"
                   data-toggle="tooltip" title="Track and publish your research results."
                   href="{{route('projects.datasets.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-book me-2"></i>
                    Datasets
                </a>
            </li>

            {{--            @if(isInBeta('charts'))--}}
            {{--                <li class="nav-item">--}}
            {{--                    <a class="nav-link fs-11 ms-5 {{setActiveNavByName('projects.charts.create')}}"--}}
            {{--                       href="{{route('projects.charts.create', [$project])}}">--}}
            {{--                        <i class="fa-fw fas fa-chart-bar me-2"></i> Charts--}}
            {{--                    </a>--}}
            {{--                </li>--}}
            {{--            @endif--}}

            @if(isInBeta('mc-server'))
{{--                <li class="nav-item mt-3">--}}
{{--                    <span class="ms-3 fs-11"><i class="fas fa-rocket me-2"></i>Desktop App</span>--}}
{{--                </li>--}}
                <livewire:projects.desktop-app.connected-clients :project="$project"/>
            @endif

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-tools me-2"></i>Actions</span>
                <span class="ms-4" id="project-sidenav-actions">{{-- Offset for tour --}}</span>
            </li>

            @if(isInBeta('datahq'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.queryhq')}}"
                       data-toggle="tooltip" title="Query your data"
                       href="{{route('projects.queryhq.index', [$project])}}">
                        <i class="fa-fw fas fa-filter me-2"></i>
                        Query Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.datahq')}}"
                       data-toggle="tooltip" title="Explore, chart and query your data."
                       href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'samples', 'context' => 'project'])}}">
                        <i class="fa-fw fas fa-binoculars me-2"></i>
                        Explore Data
                    </a>
                </li>
            @endif

            @if (isInBeta('networkhq'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.networkhq')}}"
                       data-toggle="tooltip" title="Create charts from your data."
                       href="{{route('projects.networkhq', [$project])}}">
                        <i class="fa-fw fas fa-project-diagram me-2"></i>
                        Load Network Data
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3"
                   data-toggle="tooltip" title="Create and publish a dataset."
                   href="{{route('projects.datasets.create', [$project])}}">
                    <i class="fa-fw fas fa-file-export me-2"></i>
                    Publish Data
                </a>
            </li>


            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-exchange-alt me-2"></i>File Transfer</span>
                <span class="ms-4" id="project-sidenav-actions">{{-- Offset for tour --}}</span>
            </li>

            <li class="nav-item">
                <a href="{{route('projects.upload-files', [$project])}}"
                   class="nav-link fs-11 ms-3">
                    <i class="fas fa-fw fa-upload me-2"></i>
                    Start Web Upload
                </a>
            </li>

            <x-projects.show-old-globus-side-nav :project="$project" :user="auth()->user()"/>

            @if(isInBeta("globusng2"))
                <x-projects.show-globus2-side-nav :project="$project"/>
            @endif

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-cogs me-2"></i>Project Management</span>
                <span class="ms-4" id="project-sidenav-organization">{{-- Offset for tour --}}</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.users')}}"
                   data-toggle="tooltip" title="Control who has access to your project."
                   href="{{route('projects.users.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-users-cog me-2"></i>
                    Project Members
                </a>
            </li>

            @if(isInBeta('run_scripts'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.runs')}}"
                       data-toggle="tooltip"
                       title="View job run status and results"
                       href="{{route('projects.runs.index', [$project])}}">
                        <i class="fa-fw fas fa-terminal me-2"></i>
                        Run Results
                    </a>
                </li>

                {{--                <li class="nav-item">--}}
                {{--                    <a class="nav-link fs-11 ms-5 {{setActiveNavByName('projects.triggers')}}"--}}
                {{--                       data-toggle="tooltip"--}}
                {{--                       title="View and create triggers that run scripts for specified events"--}}
                {{--                       href="{{route('projects.triggers.index', [$project])}}">--}}
                {{--                        <i class="fa-fw fas fa-bolt me-2 "></i>--}}
                {{--                        Triggers--}}
                {{--                    </a>--}}
                {{--                </li>--}}
            @endif
        </ul>
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
