{{--
  Prototype v2 KPI stat cards.
  Replaces the mixed text+icon stats in _projects-overview.blade.php.
  Drop this in as a row above the existing layout.

  Variables expected (all already passed to the dashboard view):
    $projects, $activeProjects, $userProjectsCount, $otherProjectsCount,
    $projectsWithErrorStateCount, $projectsWithWarningStateCount
--}}
<div class="row g-3 mb-4">

    {{-- Total Projects --}}
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Total Projects</div>
                <div class="display-6 fw-bold text-primary">{{count($projects)}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-user-circle me-1"></i>{{$userProjectsCount}} yours &nbsp;
                    <i class="fas fa-users me-1"></i>{{$otherProjectsCount}} shared
                </div>
            </div>
        </div>
    </div>

    {{-- Active Projects --}}
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Active</div>
                <div class="display-6 fw-bold text-warning">{{count($activeProjects)}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-star me-1"></i>marked as active
                </div>
            </div>
        </div>
    </div>

    {{-- Published Datasets --}}
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Published Datasets</div>
                <div class="display-6 fw-bold text-success">{{$publishedDatasetsCount}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-database me-1"></i>publicly available
                </div>
            </div>
        </div>
    </div>

    {{-- Archived --}}
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Archived</div>
                <div class="display-6 fw-bold text-secondary">{{$archivedCount}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-archive me-1"></i>archived projects
                </div>
            </div>
        </div>
    </div>

    {{-- Health warnings - only shown when there's something to flag --}}
    @if($projectsWithErrorStateCount > 0)
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm border-danger" style="border-left: 4px solid #dc3545 !important;">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Problems</div>
                <div class="display-6 fw-bold text-danger">{{$projectsWithErrorStateCount}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-exclamation-triangle me-1 text-danger"></i>need attention
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($projectsWithWarningStateCount > 0)
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body text-center p-3">
                <div class="text-muted small mb-1">Flagged</div>
                <div class="display-6 fw-bold text-warning">{{$projectsWithWarningStateCount}}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    <i class="fas fa-exclamation-circle me-1 text-warning"></i>warnings
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
