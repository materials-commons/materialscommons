<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('projects.show')}}"
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
                   href="{{route('projects.folders.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-folder mr-2"></i>
                    Files
                </a>
            </li>

            @if($nav_trash_count > 0)
                <li class="nav-item ml-3">
                    <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.trashcan')}}"
                       href="{{route('projects.trashcan.index', [$project])}}">
                        <i class="fa-fw fas fa-trash-restore mr-2"></i>
                        Trash
                    </a>
                </li>
            @else
                <li class="nav-item ml-3">
                    <a class="nav-link fs-11 ml-5 nav-disabled"
                       href="#">
                        <i class="fa-fw fas fa-trash mr-2"></i>
                        Trash
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.entities')}}"
                   href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">
                    <i class="fa-fw fas fa-cubes mr-2 "></i>
                    Samples
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.computations.entities.index')}}"
                   href="{{route('projects.activities.computations.index', ['project' => $project->id, 'category' => 'computational'])}}">
                    <i class="fa-fw fas fa-square-root-alt mr-2 "></i>
                    Computations
                </a>
            </li>

            <li class="nav-item mt-2">
                <span class="ml-5">Organization</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.experiments')}}"
                   data-toggle="tooltip" title="An experiment is..."
                   href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-flask mr-2"></i>
                    Experiments
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.datasets')}}"
                   href="{{route('projects.datasets.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-book mr-2"></i>
                    Datasets
                </a>
            </li>

            <li class="nav-item mt-2">
                <span class="ml-5">Actions</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5" href="{{route('projects.datasets.create', [$project])}}">
                    <i class="fa-fw fas fa-file-export mr-2"></i>
                    Publish Data
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.users')}}"
                   href="{{route('projects.users.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-users-cog mr-2"></i>
                    Project Members
                </a>
            </li>

            <x-projects.show-old-globus-side-nav :project="$project" :user="auth()->user()"/>

            @if(isInBeta())
                <x-projects.show-globus-side-nav :project="$project"/>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-city mr-2"></i>
                    My Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}" href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user mr-2"></i>
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11" href="{{route('public.index')}}">
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
    </script>
@endpush
