<table id="globus-downloads" class="table table-hover">
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
    @foreach($globusDownloads as $download)
        <tr>
            <td>{{$download->name}}</td>
            {{--            <td>{{$download->description}}</td>--}}
            <td>
                @if($download->status == \App\Enums\GlobusStatus::Done && $user->id == $download->owner_id)
                    <a href="{{$download->globus_url}}" target="_blank">Goto Globus</a>
                @endif
            </td>
            @if($showProject)
                <td>{{$download->project->name}}</td>
            @endif
            <td>{{$download->owner->name}}</td>
            <td>{{$download->updated_at->diffForHumans()}}</td>
            <td>
                @if ($download->status == \App\Enums\GlobusStatus::NotStarted)
                    Waiting to start creating project download
                @elseif ($download->status == \App\Enums\GlobusStatus::Loading)
                    Creating project download
                @else
                    Ready to use
                @endif
            </td>
            <td>
                <a href="{{route('projects.globus.downloads.delete', [$download->project, $download])}}"
                   class="btn btn-sm btn-danger">
                    <i class="fas fa-fw fa-trash-alt"></i> delete
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        mcutil.setupDatatableOnDocumentReady('globus-downloads');
    </script>
@endpush