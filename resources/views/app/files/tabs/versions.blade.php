<table id="file-versions" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Create On</th>
        <th>Size</th>
        <th>Real Size</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($previousVersions as $filever)
        <tr>
            <td>
                <a href="{{route('projects.files.show', [$project, $filever])}}">{{$filever->name}}</a>
            </td>
            <td>{{$filever->created_at->diffForHumans()}}</td>
            <td>{{$filever->toHumanBytes()}}</td>
            <td>{{$filever->size}}</td>
            <td>
                @if($filever->isImage())
                    <a href="{{route('projects.files.display', [$project, $filever])}}">

                        <img src="{{route('projects.files.display', [$project, $filever])}}"
                             style="width: 12rem">
                    </a>
                @endif
            </td>
            <td>
                <a class="action-link" href="#">
                    <i class="fas fa-history mr-2"></i>Set as current version
                </a>
                <br>
                <a class="action-link"
                   href="{{route('projects.files.download', [$project, $filever])}}">
                    <i class="fas fa-download mr-2"></i>Download File
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#file-versions').DataTable({
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush