{{-- Organization Section --}}
<div class="sidebar-group">
    <div class="sidebar-group-header" id="project-sidenav-organization">
        <i class="fas fa-sitemap fa-sm me-2 mb-1 ms-1"></i>Organization
    </div>
    <ul class="nav flex-column">
        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByName('projects.experiments')}}"
               data-toggle="tooltip" title="Track your experimental and computational studies."
               href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                <i class="fa-fw fas fa-flask me-2 mb-1 fs-11"></i>Studies
            </a>
        </li>

        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByName('projects.datasets')}}"
               data-toggle="tooltip" title="Track and publish your research results."
               href="{{route('projects.datasets.index', ['project' => $project->id])}}">
                <i class="fa-fw fas fa-book me-2 mb-1 fs-11"></i>Datasets
            </a>
        </li>

        @if(isInBeta('charts'))
            <li class="nav-item ms-2">
                <a class="nav-link fs-12 {{setActiveNavByName('projects.charts.create')}}"
                   href="{{route('projects.charts.create', [$project])}}">
                    <i class="fa-fw fas fa-chart-bar me-2 mb-1 fs-11"></i>Charts
                </a>
            </li>
        @endif
    </ul>
</div>
