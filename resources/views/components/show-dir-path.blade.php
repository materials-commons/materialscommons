<span class="d-block mb-3 fs-5 bg-white p-2 rounded">
    <i class="fas fa-folder-open text-primary me-2"></i>
    @if(sizeof($dirPaths) == 1)
        <a class="no-underline fw-bold"
           href="{{route('projects.folders.by_path', ['project' => $project, 'path' => "/"])}}">
            @if(is_null($file))
                / (root)
            @else
                /
            @endif
        </a>
    @else
        @foreach($dirPaths as $dirpath)
            <a class="no-underline fw-bold"
               href="{{route('projects.folders.by_path', ['project' => $project, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
    @if(!is_null($file))
        {{$file->name}}
    @endif
</span>
