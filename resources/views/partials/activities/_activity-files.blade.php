<ul>
    @foreach($activity->files as $f)
        <li>
            <a href="{{route('projects.files.show', [$project, $f])}}">{{$f->name}}</a>
        </li>
    @endforeach
</ul>