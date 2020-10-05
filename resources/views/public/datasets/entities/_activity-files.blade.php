<ul class="list-unstyled mb-2">
    @foreach($activity->files as $f)
        @if($loop->iteration < 12)
            <li class="mt-2">
                <a href="{{route('public.datasets.files.show', [$dataset, $f])}}" class="fs-9">{{$f->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <a href="{{route('public.datasets.files.display', [$dataset, $file])}}">
                                        <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
                                             class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @else
            <li class="{{$activity->uuid}} mt-2" hidden>
                <a href="{{route('public.datasets.files.show', [$dataset, $f])}}" class="fs-9">{{$f->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <a href="{{route('public.datasets.files.display', [$dataset, $file])}}">
                                        <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
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