{{--<a class="btn btn-success" href="{{route('projects.create')}}">--}}
{{--    <i class="fas fa-plus me-2"></i>Create Project--}}
{{--</a>--}}
{{--@if(auth()->id() == 65 || auth()->id() == 130)--}}
{{--    <a href="{{route('dashboards.webdav.reset-state')}}" class="btn btn-success">Reset Webdav State</a>--}}
{{--@endif--}}
{{--<br>--}}
{{--<br>--}}

<div class="row">
    <div class="col-lg-4">
        @include('app.dashboard.tabs.projects._projects-overview')
        @include('app.dashboard.tabs.projects._active-projects')
        @include('app.dashboard.tabs.projects._recently-accessed-projects')
    </div>
    <div class="col-lg-8">
        <div class="table-container">
            <div class="card table-card">
                <div class="card-body inner-card">
                    @include('app.dashboard.tabs.projects._projects-table')
                </div>
            </div>
        </div>
    </div>
</div>
