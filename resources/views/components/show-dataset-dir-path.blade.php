<div>
    @if(sizeof($dirPaths) == 1)
        <i class="fa fas fa-folder-open me-3"></i>/
    @else
        <i class="fa fas fa-folder-open me-3"></i>
        @foreach($dirPaths as $dirpath)
            <a class="action-link"
               href="{{route('public.datasets.folders.by-path', ['dataset' => $dataset, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
</div>
