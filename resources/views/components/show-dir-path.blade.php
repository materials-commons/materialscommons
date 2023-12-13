<span>
    @if(sizeof($dirPaths) == 1)
        <a class="action-link"
           href="{{route('projects.folders.by_path', ['project' => $project, 'path' => "/"])}}">/</a>
    @else
        @foreach($dirPaths as $dirpath)
            <a class="action-link"
               href="{{route('projects.folders.by_path', ['project' => $project, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
</span>
