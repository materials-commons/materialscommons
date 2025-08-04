<table id="experiments" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Study</th>
        <th>Summary</th>
        <th>Owner</th>
        <th>Updated</th>
        <th>Date</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($project->experiments as $experiment)
        <tr>
            <td>
                <a href="{{route('projects.experiments.show', [$project, $experiment])}}">
                    {{$experiment->name}}
                </a>
            </td>
            <td>{{$experiment->summary}}</td>
            <td>{{$experiment->owner->name}}</td>
            <td>{{$experiment->updated_at->diffForHumans()}}</td>
            <td>{{$experiment->updated_at}}</td>
            <td>
                <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                   class="action-link">
                    <i class="fas fa-fw fa-eye"></i>
                </a>
                <a href="{{route('projects.experiments.edit', [$project, $experiment])}}"
                   class="action-link">
                    <i class="fas fa-fw fa-edit"></i>
                </a>
                @if(auth()->id() == $experiment->owner_id || auth()->id() == $project->owner_id)
                    <a href="{{route('projects.experiments.delete', [$project, $experiment])}}"
                       class="action-link">
                        <i class="fas fa-fw fa-trash-alt"></i>
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#experiments').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            $('#experiments').DataTable({
                pageLength: 100,
                stateSave: true,
                columnDefs: [
                    {orderData: [4], targets: [3]},
                    {targets: [4], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
