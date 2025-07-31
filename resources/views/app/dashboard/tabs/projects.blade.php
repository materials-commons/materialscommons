{{--<a class="btn btn-success" href="{{route('projects.create')}}">--}}
{{--    <i class="fas fa-plus mr-2"></i>Create Project--}}
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

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#projects').DataTable().destroy();
        }, {once: true});

        let projectsCount = "{{sizeof($projects)}}";
        $(document).ready(() => {
            if (projectsCount === "0") {
                $('#welcome-dialog').modal();
            }
            // 0 <th>Project</th>
            // 1 <th>Size</th>
            // 2 <th>Hidden Size</th>
            // 3 <th>Files</th>
            // 4 <th>Samples</th>
            // 5 <th>Computations</th>
            // 6 <th>Owner</th>
            // 7 <th>Updated</th>
            // 8 <th>Date</th>
            // 9 <th></th>
            $('#projects').DataTable({
                stateSave: true,
                pageLength: 100,
                columnDefs: [
                    {targets: [2], visible: false}, // Hidden Size
                    {targets: [8], visible: false, searchable: false}, // Date
                    {orderData: [2], targets: [1]}, // Sort Size [1] on Hidden Size [2]
                    {orderData: [8], targets: [7]} // Sort Updated [7] on Date [8]
                ],
            });
        });
    </script>
@endpush
