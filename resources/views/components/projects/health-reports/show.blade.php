<div class="health-report">
    <div class="card">
        <x-projects.health-reports._header :health-status="$getHealthStatus()"/>

        <div class="card-body background-white">
            <div class="project-info mb-4">
                <h5><i class="fas fa-project-diagram"></i> {{ $healthReport->project->name }}</h5>
                <small class="text-muted">Project ID: {{ $healthReport->project->id }}</small>
            </div>

            <x-projects.health-reports._summary :health-report="$healthReport"/>

            @if($getTotalIssues() > 0)
                <x-projects.health-reports._show-issues :health-report="$healthReport"/>
            @else
                <x-projects.health-reports._no-issues/>
            @endif
        </div>

        <x-projects.health-reports._footer/>
    </div>
</div>
