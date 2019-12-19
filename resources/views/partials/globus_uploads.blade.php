<table id="globus-uploads" class="table table-hover">
    <thead>
    <tr>
        <th>Name</th>
{{--        <th>Description</th>--}}
        <th>Link</th>
        @if($showProject)
            <th>Project</th>
        @endif
        <th>Who</th>
        <th>Last Updated</th>
        <th>Status</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($globusUploads as $upload)
        <tr>
            <td>{{$upload->name}}</td>
            {{--            <td>{{$upload->description}}</td>--}}
            <td>
                @if($upload->uploading && $user->id == $upload->owner_id)
                    <a href="{{$upload->globus_url}}" target="_blank">Goto Globus</a>
                @endif
            </td>
            @if($showProject)
                <td>{{$upload->project->name}}</td>
            @endif
            <td>{{$upload->owner->name}}</td>
            <td>{{$upload->updated_at->diffForHumans()}}</td>
            <td>
                @if($upload->uploading)
                    Open for Uploads/Uploading files
                @elseif ($upload->loading)
                    Processing files
                @else
                    Waiting to process files
                @endif
            </td>
            <td>
                @if ($upload->uploading && $user->id == $upload->owner_id)
                    <a href="{{route('projects.globus.uploads.done', [$project, $upload])}}"
                       class="btn btn-sm btn-success">
                        <i class="fas fa-fw fa-check-circle"></i> done
                    </a>
                    <a href="{{route('projects.globus.uploads.delete', [$project, $upload])}}"
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-fw fa-trash-alt"></i> delete
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        mcutil.setupDatatableOnDocumentReady('globus-uploads');
    </script>
@endpush