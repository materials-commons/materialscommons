<ul>
    @foreach($activity->files as $f)
        @if($loop->iteration < 12)
            <li>
                <a href="{{route('projects.files.show', [$project, $f])}}">{{$f->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <a href="{{route('projects.files.display', [$project, $file])}}">
                                        <img src="{{route('projects.files.display', [$project, $file])}}"
                                             class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @else
            <li class="{{$activity->uuid}}" hidden>
                <a href="{{route('projects.files.show', [$project, $f])}}">{{$f->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <a href="{{route('projects.files.display', [$project, $file])}}">
                                        <img src="{{route('projects.files.display', [$project, $file])}}"
                                             class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @endif
        @include('common.show-more-control', [
            'items' => $files,
            'attrName' => $activity->uuid,
            'msg' => 'files...'
        ])
    @endforeach
</ul>