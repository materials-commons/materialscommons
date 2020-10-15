@if(isset($project->file_types) && sizeof($project->file_types) != 0)
    <div class="form-group">
        <label for="file-types">File Types ({{sizeof($project->file_types)}})</label>
        <ul class="list-inline">
            @foreach($project->file_types as $type => $count)
                <li class="list-inline-item mt-1">
                    <span class="fs-9 grey-5">{{$type}} ({{$count}})</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif