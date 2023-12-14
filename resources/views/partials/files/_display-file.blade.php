@if($fileExists($file))
    @switch($fileType($file))
        @case("image")
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        @if(isset($displayRoute))
                            <div class="text-center">
                                <a href="{{$displayRoute}}">
                                    <img src="{{$displayRoute}}" class="img-fluid">
                                </a>
                            </div>
                        @else
                            <div class="text-center">
                                <img src="{{$displayRoute}}" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @break

        @case("text")
            <div class="ml-3">
                @if(isset($displayRoute))
                    <a href="{{$displayRoute}}">Fullscreen</a>
                    @if($file->size <= 2000000)
                        <a href="#" onclick="mcutil.copyToClipboard('#file-contents')" class="ml-2">
                            <i class="fa fas fa-clone"></i>
                        </a>
                    @endif
                    <br/>
                    <br/>
                @endif
                @if($file->size > 2000000)
                    <span class="ml-3">File too large to display</span>
                @else
                    <pre id="file-contents">{{$fileContents($file)}}</pre>
                @endif
            </div>
            @break

        @case("open-visus")
            @php
                $visusDatasetUrl = \App\Services\OpenVisusApiService::visusDatasetUrl("{$file->uuid}_{$file->name}");
            @endphp
            <div style="height:750px">
                <iframe src="{{$visusDatasetUrl}}" width="100%" height="100%"></iframe>
            </div>
            @break

        @case("jupyter-notebook")
            <div style="height:750px">
                <iframe src="{{$displayRoute}}" width="100%" height="100%"></iframe>
            </div>
            @break

        @case("pdf")
            <div class="embed-responsive embed-responsive-4by3">
                <embed class="col-xs-8 embed-responsive-item"
                       src="{{$displayRoute}}">
            </div>
            @break

        @case("excel")
            @if($file->size > 2000000)
                <span class="ml-3">Excel file too large to display</span>
            @else
                @include('partials.files._display-excel-file')
            @endif
            @break

        @case("office")
            <div class="embed-responsive embed-responsive-4by3">
                <embed class="col-xs-8 embed-responsive-item"
                       src="{{$displayRoute}}">
            </div>
            @break

        @case("video")
            <div class="col-10">
                <a href="{{$displayRoute}}" class="mt-2">
                    Display Full Screen
                </a>
                <video controls width="100%" src="{{$displayRoute}}" class="mt-2">
                </video>
            </div>
            @break

        @case("html")
            <div class="ml-3">
                @if($file->size > 2000000)
                    <span class="ml-3">File too large to display</span>
                @else
                    <pre>{!!$fileContents($file)!!}</pre>
                @endif
            </div>
            @break

        @case("markdown")
            <x-markdown flavor="github">{!!$fileContents($file)!!}</x-markdown>
            @break

        @default
            <span class="ml-3">Unable to display files of type {{$fileType($file)}}</span>
    @endswitch
@else
    <span class="ml-3">Unable to display file, it may not exist or have been converted yet</span>
@endif
