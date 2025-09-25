<x-table-container>
    <br>
    <table id="etl-runs" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Log</th>
            <th>Date</th>
            <th>Created</th>
            <th>File</th>
        </tr>
        </thead>
        <tbody>
        @foreach($experiment->etlRuns as $etlRun)
            @if(isset($etlRun->files[0]))
                <tr>
                    <td>
                        <a href="{{route('projects.experiments.etl_run.show', [$project, $experiment, $etlRun])}}">
                            View Log
                        </a>
                    </td>
                    <td>{{$etlRun->created_at->diffForHumans()}}</td>
                    <td>{{$etlRun->created_at}}</td>
                    <td>
                        <a href="{{route('projects.files.show', [$project, $etlRun->files[0]])}}">
                            {{$etlRun->files[0]->name}}
                        </a>
                    </td>
                </tr>
            @else
                <tr>
                    <td>
                        <a href="{{route('projects.experiments.etl_run.show', [$project, $experiment, $etlRun])}}">
                            View Log
                        </a>
                    </td>
                    <td>{{$etlRun->created_at->diffForHumans()}}</td>
                    <td>{{$etlRun->created_at}}</td>
                    <td>
                        From Google Sheet
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</x-table-container>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#etl-runs').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [2], targets: [1]},
                    {targets: [2], visible: false, searchable: false}
                ]
            });
        });
    </script>
@endpush
