<div class="card mb-4">
    <div class="card-body inner-card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-clock mr-2"></i>Recently Accessed</h5>
            <span class="badge bg-primary fs-11" style="color:#ffffff">{{count($recentlyAccessedProjects)}}</span>
        </div>
        <hr/>
        @if(auth()->user()->hasRecentlyAccessedProjectsThatAreNotActive())
            <div class="projects-list">
                @foreach($recentlyAccessedProjects as $proj)
                    @include('app.dashboard.tabs.projects._project-card')
                @endforeach
            </div>
        @else
            <div class="text-center p-4">
                <i class="fas fa-clock text-muted mb-2" style="font-size: 2rem;"></i>
                <p class="text-muted mb-0">No recent projects that aren't active</p>
            </div>
        @endif
    </div>
</div>
