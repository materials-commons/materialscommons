<div class="health-report">
    <div>
        <x-projects.health-reports._header :health-status="$getHealthStatus()"/>

        <div class="background-whitex">
            <div class="project-info mb-4 mt-3">
                <h5><i class="fas fa-project-diagram"></i> {{ $healthReport->project->name }}</h5>
                <small class="text-muted">Project ID: {{ $healthReport->project->id }}</small>
            </div>

            <livewire:projects.health-reports.health-report-tabs :health-report="$healthReport"/>

        </div>

        <x-projects.health-reports._footer :created-at="$healthReport->createdAt"/>
    </div>
</div>
