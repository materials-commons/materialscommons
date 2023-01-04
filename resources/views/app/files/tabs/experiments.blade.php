<table id="experiments" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Experiment</th>
        <th>Summary</th>
        <th>Updated</th>
        <th>Date</th>
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
            <td>{{$experiment->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#experiments').DataTable({
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
