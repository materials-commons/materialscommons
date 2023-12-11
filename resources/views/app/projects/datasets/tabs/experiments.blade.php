@include('app.projects.datasets.tabs._short-overview')

<table id="dt-table" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Summary</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dataset->experiments as $experiment)
        <tr>
            <td>
                <a href="{{route('projects.experiments.show', [$project, $experiment])}}">
                    {{$experiment->name}}
                </a>
            </td>
            <td>{{$experiment->summary}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(() => {
                $('#dt-table').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        });
    </script>
@endpush
