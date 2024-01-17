<div class="card bg-light col-lg-4 col-md-6 col-sm-8">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{route('projects.show', [$proj])}}">{{$proj->name}}</a>
            @if (auth()->user()->isActiveProject($proj))
                <a href="{{route('dashboard.project.unmark-as-active', [$proj])}}"
                   data-toggle="tooltip" title="Marks project as inactive and removes it from the active projects list."
                   class="float-right cursor-pointer card-link"><i class="fa fas fa-check"></i></a>
            @else
                <a href="{{route('dashboard.project.mark-as-active', [$proj])}}"
                   data-toggle="tooltip" title="Marks project as active and places it into the active projects list."
                   class="float-right cursor-pointer grey-8"><i class="fa fas fa-check"></i></a>
            @endif
        </h5>
        <h6 class="card-subtitle mb-2 text-muted">Last
            Accessed: {{Carbon\Carbon::parse($recentlyAccessedOn)->diffForHumans()}}</h6>
        <h6 class="card-subtitle mb-2 text-muted">
            # Files: {{number_format($proj->file_count)}}/{{formatBytes($proj->size)}}
        </h6>
        <a href="{{route('projects.folders.index', [$proj])}}" class="card-link"><i
                    class="fa fas fa-folder"></i></a>
        <a href="{{route('projects.entities.index', [$proj])}}" class="card-link"><i
                    class="fa fas fa-cubes"></i></a>
        <a href="{{route('projects.computations.entities.index', [$proj])}}" class="card-link"><i
                    class="fa fas fa-square-root-alt"></i></a>
        <a href="{{route('projects.experiments.index', [$proj])}}" class="card-link"><i
                    class="fa fas fa-flask"></i></a>
    </div>
</div>