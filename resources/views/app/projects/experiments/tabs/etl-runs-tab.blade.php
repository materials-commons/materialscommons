<x-show-standard-details :item="$experiment"/>
<hr>
<br>
<table id="etl-runs" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>File</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($experiment->etlRuns as $etlRun)
        <tr>
            <td>
                <a href="{{route('projects.experiments.etl_run.show', [$project, $experiment, $etlRun])}}">
                    {{$etlRun->files[0]->name}}
                </a>
            </td>
            <td>
                {{$etlRun->created_at->diffForHumans()}}
            </td>
        </tr>
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