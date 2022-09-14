<div>
    @if(sizeof($dirPaths) == 1)
        /
    @else
        @foreach($dirPaths as $dirpath)
            <a class="action-link"
               href="{{route('projects.folders.by_path', ['project' => $project, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
</div>