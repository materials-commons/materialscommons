<div class="health-report">
    <div class="card">
        <x-projects.health-reports._header :health-status="$getHealthStatus()"/>

        <div class="card-body background-white">
            <div class="project-info mb-4">
                <h5><i class="fas fa-project-diagram"></i> {{ $healthReport->project->name }}</h5>
                <small class="text-muted">Project ID: {{ $healthReport->project->id }}</small>
            </div>

            <livewire:projects.health-reports.health-report-tabs :health-report="$healthReport"/>

        </div>

        <x-projects.health-reports._footer/>
    </div>
</div>
