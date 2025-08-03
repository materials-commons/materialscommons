<ul class="list-unstyled mb-2">
    @foreach($activity->files as $f)
        @if($loop->iteration < 12)
            <li class="mt-2 ml-3">
                @if($f->mime_type == "directory")
                    <a href="{{route('projects.folders.show', [$project, $f])}}">{{$f->path}} (directory)</a>
                @else
                    <a href="{{route('projects.files.show', [$project, $f])}}">{{$f->name}}</a>
                @endif
                @if(in_array($f->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center mb-2 mt-2">
                                    <a href="{{route('projects.files.display', [$project, $f])}}">
                                        <img src="{{route('projects.files.display', [$project, $f])}}"
                                             class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @else
            <li class="{{$activity->uuid}} mt-2 ml-3" hidden>
                @if($f->mime_type == "directory")
                    <a href="{{route('projects.folders.show', [$project, $f])}}">{{$f->path}} (directory)</a>
                @else
                    <a href="{{route('projects.files.show', [$project, $f])}}">{{$f->name}}</a>
                @endif
                @if(in_array($f->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center mb-2 mt-2">
                                    <a href="{{route('projects.files.display', [$project, $f])}}">
                                        <img src="{{route('projects.files.display', [$project, $f])}}"
                                             class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @endif
    @endforeach
    @include('common.show-more-control', [
           'items' => $activity->files,
           'attrName' => $activity->uuid,
           'msg' => 'files...'
       ])
</ul>
