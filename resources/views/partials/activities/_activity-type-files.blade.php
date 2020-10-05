<ul class="list-unstyled mb-2">
    @foreach($files as $file)
        @if($loop->iteration < 12)
            <li class="mt-2">
                <a href="{{route('projects.files.show', [$project, $file->fid])}}"
                   class="fs-9">{{$file->fname}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center mb-2 mt-2">
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
        @else
            <li class="{{Illuminate\Support\Str::slug($activityType->name)}} mt-2" hidden>
                <a href="{{route('projects.files.show', [$project, $file->fid])}}" class="fs-9">{{$file->fname}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center mb-2 mt-2">
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
        @endif
    @endforeach
    @include('common.show-more-control', [
        'items' => $files,
        'attrName' => Illuminate\Support\Str::slug($activityType->name),
        'msg' => 'files...'
    ])
</ul>
