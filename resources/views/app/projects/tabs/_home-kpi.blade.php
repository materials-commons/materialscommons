<div class="row g-2 mb-3">

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.folders.show', [$project, $project->rootDir])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2 background-white">
                    <div class="text-muted small mb-1">Files</div>
                    <div class="fw-bold fs-5 text-primary">{{number_format($project->file_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{number_format($project->directory_count)}} dirs
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.experiments.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2 background-white">
                    <div class="text-muted small mb-1">Studies</div>
                    <div class="fw-bold fs-5 text-info">{{number_format($project->experiments_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">experimental</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.entities.index', [$project, 'category' => 'experimental'])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2 background-white">
                    <div class="text-muted small mb-1">Samples</div>
                    <div class="fw-bold fs-5 text-teal">{{number_format($project->entities_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">tracked</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.datasets.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2 background-white">
                    <div class="text-muted small mb-1">Datasets</div>
                    <div class="fw-bold fs-5 text-success">{{number_format($project->published_datasets_count)}}</div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{number_format($project->unpublished_datasets_count)}} unpublished
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-2 background-white">
                <div class="text-muted small mb-1">Storage</div>
                <div class="fw-bold fs-5 text-secondary">{{formatBytes($project->size)}}</div>
                <div class="text-muted" style="font-size:.65rem;">total used</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <a href="{{route('projects.users.index', [$project])}}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body text-center p-2 background-white">
                    <div class="text-muted small mb-1">Members</div>
                    <div class="fw-bold fs-5 text-primary">
                        {{$project->team->members->count()}}
                    </div>
                    <div class="text-muted" style="font-size:.65rem;">
                        {{$project->team->admins->count()}} admin(s)
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>
