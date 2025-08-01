<div class="card mb-4">
    <div class="card-body inner-card">
        <h5 class="card-title">Projects Overview</h5>
        <hr/>
        <div class="mt-2 mb-4 text-center">
            <a class="text-primary" href="/mcdocs2/getting_started/first_project.html" target="_blank">
                Getting Started Guide <i class="fas fa-external-link-alt ml-1"></i>
            </a>
        </div>

        <div class="row">
            <div class="col-6">
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
            </div>
            <div class="col-6">
                <div class="stats-container">
                    <div class="stat-item mb-3">
                        <i class="fas fa-user-circle text-primary mr-2"></i>
                        Your Projects: {{$userProjectsCount}}
                    </div>
                    <div class="stat-item mb-3">
                        <i class="fas fa-users text-info mr-2"></i>
                        Other Projects:{{$otherProjectsCount}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-4">
            {{--            <h6 class="mb-3">Quick Actions</h6>--}}
            <a href="{{route('projects.create')}}" class="btn btn-primary btn-sm mb-2 w-100">
                <i class="fas fa-plus mr-2"></i>Create New Project
            </a>
        </div>
    </div>
</div>
