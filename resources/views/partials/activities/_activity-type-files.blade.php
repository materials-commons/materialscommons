<ul>
    @foreach($files as $file)
        <li>
            <a href="{{route('projects.files.show', [$project, $file->fid])}}">{{$file->fname}}</a>
            @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-center">
                                <a href="{{route('projects.files.display', [$project, $file->fid])}}">
                                    <img src="{{route('projects.files.display', [$project, $file->fid])}}"
                                         class="img-fluid">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </li>
    @endforeach
</ul>