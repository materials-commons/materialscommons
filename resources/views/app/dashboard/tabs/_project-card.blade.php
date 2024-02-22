@php
    $recentlyAccessedOn = auth()->user()->projectRecentlyAccessedOn($proj);
@endphp
<div class="card bg-light col-lg-4 col-md-6 col-sm-8">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{route('projects.show', [$proj])}}">{{$proj->name}}</a>
            @if (auth()->user()->isActiveProject($proj))
                <a href="{{route('dashboard.projects.unmark-as-active', [$proj])}}"
                   data-toggle="tooltip" title="Remove project from the active projects list."
                   class="float-right cursor-pointer card-link"><i class="fa fas fa-check"></i></a>
            @else
                <a href="{{route('dashboard.projects.mark-as-active', [$proj])}}"
                   data-toggle="tooltip" title="Marks project as active and places it into the active projects list."
                   class="float-right cursor-pointer grey-8"><i class="fa fas fa-check"></i></a>
            @endif
        </h5>
        <h6 class="card-subtitle mb-2 text-muted">Last
            Accessed: {{Carbon\Carbon::parse($recentlyAccessedOn)->diffForHumans()}}</h6>
        <h6 class="card-subtitle mb-2 text-muted">
            # Files: {{number_format($proj->file_count)}}/{{formatBytes($proj->size)}}
        </h6>

        <a href="{{route('projects.folders.index', [$proj])}}"
           data-toggle="tooltip"
           title="Goto top level folder for project."
           class="card-link"><i class="fa fas fa-folder"></i></a>

        <a href="{{route('projects.entities.index', [$proj])}}"
           data-toggle="tooltip"
           title="Goto samples for project."
           class="card-link"><i class="fa fas fa-cubes"></i></a>

        <a href="{{route('projects.computations.entities.index', [$proj])}}"
           data-toggle="tooltip"
           title="Goto computations for project."
           class="card-link"><i class="fa fas fa-square-root-alt"></i></a>

        <a href="{{route('projects.experiments.index', [$proj])}}"
           data-toggle="tooltip"
           title="Goto experiments for project."
           class="card-link"><i class="fa fas fa-flask"></i></a>

        <a href="{{route('dashboard.projects.archive',[$proj])}}"
           data-toggle="tooltip"
           title="Marks project as archived. Project will show up in the Archived Projects tab."
           class="card-link"><i class="fas fa-fw fa-archive"></i></a>
    </div>
</div>