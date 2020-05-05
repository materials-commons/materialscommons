@if($fileExists)
    <p>File preview:</p>
    @switch($fileType)
        @case("image")
        <div class="col-lg-10">
            <img src="{{route('public.datasets.files.display', [$dataset, $file])}}" class="img-fluid">
        </div>
        @break

        @case("text")
        <div class="ml-3">
            <pre>{{$fileContents}}</pre>
        </div>
        @break

        @case("pdf")
        <div class="embed-responsive embed-responsive-4by3">
            <embed class="col-xs-8 embed-responsive-item"
                   src="{{route('public.datasets.files.display', [$dataset, $file])}}">
        </div>
        @break

        @case("excel")
        @include('partials.files._display-excel-file', ['fileContents' => $fileContents])
        @break

        @case("office")
        <div class="embed-responsive embed-responsive-4by3">
            <embed class="col-xs-8 embed-responsive-item"
                   src="{{route('public.datasets.files.display', [$dataset, $file])}}">
        </div>
        @break

        @default
        <span class="ml-3">Unable to display files of type {{$fileType}}</span>
    @endswitch
@else
    <span class="ml-3">Unable to display file, it may not exist or have been converted yet</span>
@endif

