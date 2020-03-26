<table id="experiments" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Experiment</th>
        <th>Summary</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($file->experiments as $experiment)
        <tr>
            <td>
                <a href="{{route('projects.experiments.show', [$project, $experiment])}}">{{$experiment->name}}</a>
            </td>
            <td>{{$experiment->summary}}</td>
            <td>{{$experiment->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#experiments').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
