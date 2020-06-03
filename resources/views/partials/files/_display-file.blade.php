@if($fileExists($file))
    @switch($fileType($file))
        @case("image")
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
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
            <pre>{!!$fileContents($file)!!}</pre>
        </div>
        @break

        @case("pdf")
        <div class="embed-responsive embed-responsive-4by3">
            <embed class="col-xs-8 embed-responsive-item"
                   src="{{$displayRoute}}">
        </div>
        @break

        @case("excel")
        @include('partials.files._display-excel-file')
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
