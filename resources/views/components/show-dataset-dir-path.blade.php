<div>
    {{--    @if ($directory->name == '/')--}}
    {{--        {{$directory->name}}--}}
    {{--    @else--}}
    @if(sizeof($dirPaths) == 1)
        /
    @else
        @foreach($dirPaths as $dirpath)
            <a class="action-link"
               href="{{route('public.datasets.folders.by-path', ['dataset' => $dataset, 'path' => $dirpath["path"]])}}">
                {{$dirpath['name']}}/
            </a>
        @endforeach
    @endif
    {{--    @endif--}}
</div>