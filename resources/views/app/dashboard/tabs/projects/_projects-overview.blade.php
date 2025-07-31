<div class="card mb-4">
    <div class="card-body inner-card">
        <h5 class="card-title">Projects Overview</h5>
        <hr/>
        <div class="stats-container">
            <div class="stat-item mb-3">
                <i class="fas fa-project-diagram text-primary mr-2"></i>
                Total Projects: {{count($projects)}}
            </div>
            <div class="stat-item mb-3">
                <i class="fas fa-star text-warning mr-2"></i>
                Active Projects: {{count($activeProjects)}}
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-4">
            <h6 class="mb-3">Quick Actions</h6>
            <a href="{{route('projects.create')}}" class="btn btn-primary btn-sm mb-2 w-100">
                <i class="fas fa-plus mr-2"></i>Create New Project
            </a>
        </div>
    </div>
</div>
