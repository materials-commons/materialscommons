<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link fs-11" href="{{route('projects.index')}}">
                    <i class="fa-fw fas fa-layer-group mr-2"></i>
                    Projects
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('projects.show')}}"
                   href="{{route('projects.show', ['project' => $project->id])}}">
                    <span data-feather="home"></span>
                    <i class="fa-fw fas fa-project-diagram mr-2"></i>
                    {{$project->name}}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.experiments')}}"
                   href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-flask mr-2"></i>
                    Experiments
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.entities')}}"
                   href="{{route('projects.entities.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-cubes mr-2 "></i>
                    Samples
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.activities')}}"
                   href="{{route('projects.activities.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-code-branch mr-2"></i>
                    Processes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.folders')}}"
                   href="{{route('projects.folders.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-folder mr-2"></i>
                    Files
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5" href="#">
                    <i class="fa-fw fas fa-cloud-upload-alt mr-2"></i>
                    Globus Upload
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5" href="#">
                    <i class="fa-fw fas fa-cloud-download-alt mr-2"></i>
                    Globus Download
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus.status')}}"
                   href="{{route('projects.globus.status', [$project])}}">
                    <i class="fa-fw fas fa-cloud mr-2"></i>
                    Globus Uploads Status
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.datasets')}}"
                   href="{{route('projects.datasets.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-book mr-2"></i>
                    Publish
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.users')}}"
                   href="{{route('projects.users.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-users-cog mr-2"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.settings')}}"
                   href="{{route('projects.settings.index', ['project' => $project->id])}}">
                    <i class="fa-fw fas fa-cogs mr-2"></i>
                    Settings
                </a>
            </li>

            <li class="nav-item">
                <br>
                <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}" href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user mr-2"></i>
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-users mr-2"></i>
                    Communities
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
