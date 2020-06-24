<ul>
    @foreach($files as $f)
        <li>
            <a href="{{route('public.datasets.files.show', [$dataset, $f->fid])}}">{{$f->fname}}</a>
        </li>
    @endforeach
</ul>