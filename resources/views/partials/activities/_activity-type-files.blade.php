<ul>
    @foreach($files as $file)
        <li>
            <a href="{{route('projects.files.show', [$project, $file->fid])}}">{{$file->name}}</a>
        </li>
    @endforeach
</ul>