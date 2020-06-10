<ul>
    @foreach($activity->files as $f)
        <li>{{$f->name}}</li>
    @endforeach
</ul>