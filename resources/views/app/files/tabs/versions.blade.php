<table id="file-versions" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Uploaded</th>
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
                @if($filever->current)
                    (Active)
                @endif
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
                @if(!$filever->current)
                    <a class="action-link" href="{{route('projects.files.set-active', [$project, $filever])}}">
                        <i class="fas fa-history mr-2"></i>Set as active version
                    </a>
                    <br>
                @endif
                <a class="action-link"
                   href="{{route('projects.files.download', [$project, $filever])}}">
                    <i class="fas fa-download mr-2"></i>Download File
                </a>
                <br>
                <a class="action-link" href="{{route('projects.files.compare', [$project, $file, $filever])}}">
                    <i class="fas fa-columns mr-2"></i>Compare
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
