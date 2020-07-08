<ul>
    @foreach($files as $file)
        <li>
            <a href="{{route('projects.files.show', [$project, $file->fid])}}">{{$file->fname}}</a>
        </li>
    @endforeach
</ul>