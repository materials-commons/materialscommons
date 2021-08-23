<x-show-standard-details :item="$experiment"/>
<hr>
<br>
<table id="etl-runs" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Log</th>
        <th>Date</th>
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
                <td>
                    {{$etlRun->created_at->diffForHumans()}}
                </td>
                <td>
                    <a href="{{route('projects.files.show', [$project, $etlRun->files[0]])}}">
                        {{$etlRun->files[0]->name}}
                    </a>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#etl-runs').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush