{{-- Data Section --}}
<div class="sidebar-group">
    <div class="sidebar-group-header" id="project-sidenav-data">
        <i class="fas fa-database fa-sm me-2 mb-1 ms-1"></i>Data
    </div>
    <ul class="nav flex-column">
        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByOneOf(['projects.folders', 'projects.files'])}}"
               data-bs-toggle="tooltip" title="Access your project files."
               href="{{route('projects.folders.show', [$project, $project->rootDir])}}">
                <i class="fa-fw fas fa-folder me-2 mb-1 fs-11"></i>Files
            </a>
        </li>

        @if($nav_trash_count > 0)
            <li class="nav-item ms-4">
                <a class="nav-link fs-12 {{setActiveNavByName('projects.trashcan')}}"
                   data-bs-toggle="tooltip" title="View your deleted files and empty the trashcan."
                   href="{{route('projects.trashcan.index', [$project])}}">
                    <i class="fa-fw fas fa-trash-restore me-2 mb-1 fs-11"></i>
                    Trash({{number_format($nav_trash_count)}})
                </a>
            </li>
        @endif

        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByName('projects.sheets.index')}}"
               data-bs-toggle="tooltip" title="View you Excel spreadsheets, CSV files and Google sheets."
               href="{{route('projects.sheets.index', [$project])}}">
                <i class="fa-fw fas fa-file-excel me-2 mb-1 fs-11"></i>Sheets
            </a>
        </li>

        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByName('projects.entities')}}"
               data-bs-toggle="tooltip"
               title="View the experimental processes and samples loaded into your project."
               href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">
                <i class="fa-fw fas fa-cubes me-2 mb-1 fs-11"></i>Samples
            </a>
        </li>

        <li class="nav-item ms-2">
            <a class="nav-link fs-12 {{setActiveNavByName('projects.computations.entities.index')}}"
               data-bs-toggle="tooltip"
               title="View the computational activities and entities loaded into your project."
               href="{{route('projects.computations.entities.index', ['project' => $project->id, 'category' => 'computational'])}}">
                <i class="fa-fw fas fa-square-root-alt me-2 mb-1 fs-11"></i>Computations
            </a>
        </li>
    </ul>
</div>
