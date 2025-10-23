
<span class="d-block mb-3 fs-5">
    <i class="fas fa-folder-open text-primary me-2"></i>
    @if(sizeof($dirPaths) == 1)
        <a class="no-underline fw-bold"
           href="{{route('projects.folders.by_path', ['project' => $project, 'path' => "/"])}}">/ (root)</a>
    @else
        @foreach($dirPaths as $dirpath)
            <a class="no-underline fw-bold"
               href="{{route('projects.folders.by_path', ['project' => $project, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
</span>
