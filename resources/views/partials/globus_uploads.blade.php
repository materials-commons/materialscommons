<table id="globus-uploads" class="table table-hover">
    <thead>
    <tr>
        @if($showProject)
            <th>Project</th>
        @endif
        <th>Who</th>
        <th>Last Updated</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($globusUploads as $upload)
        <tr>
            @if($showProject)
                <td>{{$upload->project->name}}</td>
            @endif
            <td>{{$upload->owner->name}}</td>
            <td>{{$upload->updated_at->diffForHumans()}}</td>
            <td>{{$upload->loading ? "Processing files" : "Unprocessed/Still uploading"}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        mcutil.setupDatatableOnDocumentReady('globus-uploads');
    </script>
@endpush