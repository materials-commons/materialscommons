{{-- ─── Charts section ────────────────────────────────────────────────────── --}}
<x-collapsible-section title="Analytics"
                       id="published-datasets-analytics"
                       storage-key="mc_dashboard_published_datasets_open"
                       :resize-plotly="true">
    @include('app.dashboard.tabs._published-datasets-charts')
</x-collapsible-section>

{{-- ─── Existing DataTable (unchanged) ────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Published Datasets</h5>
    <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
       title="Publish data" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-2"></i> Create Dataset
    </a>
</div>

<table id="datasets" class="table table-hover" style="width:100%"
       aria-label="Published datasets">
    <thead>
    <tr>
        <th>Dataset</th>
        <th>Summary</th>
        <th>Published</th>
        <th>Date</th>
        <th>Views</th>
        <th>Downloads</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach($publishedDatasets as $ds)
        <tr>
            <td>
                <a href="{{route('projects.datasets.show', [$ds->project_id, $ds])}}">
                    {{$ds->name}}
                </a>
            </td>
            <td>{{$ds->summary}}</td>
            <td>{{$ds->published_at->diffForHumans()}}</td>
            <td>{{$ds->published_at}}</td>
            <td>{{$ds->views_count}}</td>
            <td>{{$ds->downloads_count}}</td>
            <td>{{$ds->comments_count}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#datasets').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
