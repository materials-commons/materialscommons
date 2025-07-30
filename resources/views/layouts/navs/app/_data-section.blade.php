{{-- Data Section --}}
<div class="sidebar-group">
    <div class="sidebar-group-header" id="project-sidenav-data">
        <i class="fas fa-database fa-sm mr-2"></i>Data
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNavByOneOf(['projects.folders', 'projects.files'])}}"
               data-toggle="tooltip" title="Access your project files."
               href="{{route('projects.folders.show', [$project, $project->rootDir])}}">
                <i class="fa-fw fas fa-folder mr-2"></i>Files
            </a>
        </li>

        @if($nav_trash_count > 0)
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNavByName('projects.trashcan')}}"
                   data-toggle="tooltip" title="View your deleted files and empty the trashcan."
                   href="{{route('projects.trashcan.index', [$project])}}">
                    <i class="fa-fw fas fa-trash-restore mr-2"></i>
                    Trash({{number_format($nav_trash_count)}})
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNavByName('projects.sheets.index')}}"
               data-toggle="tooltip" title="View you Excel spreadsheets, CSV files and Google sheets."
               href="{{route('projects.sheets.index', [$project])}}">
                <i class="fa-fw fas fa-file-excel mr-2"></i>Sheets
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNavByName('projects.entities')}}"
               data-toggle="tooltip"
               title="View the experimental processes and samples loaded into your project."
               href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">
                <i class="fa-fw fas fa-cubes mr-2"></i>Samples
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNavByName('projects.computations.entities.index')}}"
               data-toggle="tooltip"
               title="View the computational activities and entities loaded into your project."
               href="{{route('projects.computations.entities.index', ['project' => $project->id, 'category' => 'computational'])}}">
                <i class="fa-fw fas fa-square-root-alt mr-2"></i>Computations
            </a>
        </li>
    </ul>
</div>
