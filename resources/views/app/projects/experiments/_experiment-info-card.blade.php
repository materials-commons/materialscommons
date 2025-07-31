<div class="card" style="border-color: #b3c2d9; position: sticky; top: 1rem;">
    <div class="card-body inner-card">
        <h5 class="card-title"><strong>Studies Overview</strong></h5>
        <hr/>
        <p class="card-text">
            Document and organize your experimental or computational research with Studies. Track processes,
            parameters, measurements, and results, all linked to your project files.
        </p>

        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-lightbulb mr-2"></i>Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{route('projects.experiments.create', [$project])}}"
                   class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Create Study
                </a>
                <a href="https://materialscommons.org/docs/docs/reference/spreadsheets/#overview"
                   target="_blank"
                   class="btn btn-outline-secondary btn-sm mr-2 mb-2">
                    <i class="fas fa-file-import mr-1"></i> Import Guide
                </a>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Statistics</h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <i class="fas fa-flask text-primary mr-2"></i>
                    Total Studies: {{$project->experiments->count()}}
                </li>
                <li class="mb-2">
                    <i class="fas fa-clock text-primary mr-2"></i>
                    Last Updated: {{$project->experiments->max('updated_at')?->diffForHumans() ?? 'No studies'}}
                </li>
            </ul>
        </div>
    </div>
</div>
