<ul>
    @foreach($activity->files as $f)
        <li>
            <a href="{{route('public.datasets.files.show', [$dataset, $f])}}">{{$f->name}}</a>
        </li>
    @endforeach
</ul>