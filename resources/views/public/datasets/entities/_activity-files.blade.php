<ul class="list-unstyled mb-2">
    @foreach($activity->files as $file)
        @if($loop->iteration < 12)
            <li class="mt-2">
                <a href="{{route('public.datasets.files.show', [$dataset, $file])}}" class="fs-10">{{$file->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    @auth
                                        <a href="{{route('public.datasets.files.display', [$dataset, $file])}}">
                                            <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
                                                 class="img-fluid">
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @else
            <li class="{{$activity->uuid}} mt-2" hidden>
                <a href="{{route('public.datasets.files.show', [$dataset, $file])}}" class="fs-10">{{$file->name}}</a>
                @if(in_array($file->mime_type, ["image/gif", "image/jpeg", "image/png", "image/tiff", "image/x-ms-bmp","image/bmp"]))
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    @auth
                                        <a href="{{route('public.datasets.files.display', [$dataset, $file])}}">
                                            <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
                                                 class="img-fluid">
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </li>
        @endif
        @include('common.show-more-control', [
            'items' => $activity->files,
            'attrName' => $activity->uuid,
            'msg' => 'files...'
        ])
    @endforeach
</ul>
