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
            <div class="ms-3 mt-2">
                @if($file->size > 20000000)
                    <span class="ms-3">File too large to display</span>
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
            @if($file->size > 10000000)
                <span class="ms-3">Excel file too large to display</span>
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
            <div class="ms-3">
                @if($file->size > 20000000)
                    <span class="ms-3">File too large to display</span>
                @else
                    <pre>{!!$fileContents($file)!!}</pre>
                @endif
            </div>
            @break

        @case("markdown")
            @php
                $contents = $fileContents($file);
            @endphp
            <div id="file-contents" style="display: none">{!!$contents!!}</div>
            <x-markdown>{!!$contents!!}</x-markdown>
            @break

        @default
            <span class="ms-3">Unable to display files of type {{$fileType($file)}}</span>
    @endswitch
@else
    @if(!$file->realFileExists())
        <h1 class="ms-3"><i class="fa fas fa-exclamation-triangle fa-2x me-2 text-danger"></i>
            File is missing. Please <a
                href="{{route('projects.folders.upload', [$file->project_id, $file->directory_id])}}">upload</a> again
            if you are able to.
        </h1>
    @elseif($file->isConvertibleImage())
        <span class="ms-3">Unable to display image, {{$file->mime_type}} type not viewable in a browser, and a JPEG for viewing hasn't been created yet.</span>
    @elseif($file->isConvertible())
        <span class="ms-3">Unable to display file, {{$file->mime_type}} type not viewable in a browser, and a conversion for viewing hasn't been created yet.</span>
    @else
        <span class="ms-3">Unable to display file, {{$file->mime_type}} not viewable in a browser.</span>
    @endif
@endif
