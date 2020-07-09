@if($fileExists($file))
    @switch($fileType($file))
        @case("image")
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="text-center">
                        <a href="{{$displayRoute}}">
                            <img src="{{$displayRoute}}" class="img-fluid">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @break

        @case("text")
        <div class="ml-3">
            @if($file->size > 2000000)
                <span class="ml-3">File too large to display</span>
            @else
                <pre>{!!$fileContents($file)!!}</pre>
            @endif
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

        @default
        <span class="ml-3">Unable to display files of type {{$fileType($file)}}</span>
    @endswitch
@else
    <span class="ml-3">Unable to display file, it may not exist or have been converted yet</span>
@endif
