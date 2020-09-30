@isset($objectCounts->filesCount)
    <h5>There are {{$objectCounts->filesCount}} files totalling {{formatBytes($totalFilesSize)}}.</h5>
@endisset
@include('partials.overview._file-types')
@include('partials.overview._process-types')

{{--<div class="row ml-1">--}}
{{--    <div class="@isset($fileDescriptionTypes) col-4 @else col-5 @endisset bg-grey-9">--}}

{{--    </div>--}}
{{--    <div class="@isset($fileDescriptionTypes) col-3 @else col-5 @endisset bg-grey-9 ml-2">--}}
{{--        @include('partials.overview._object-types')--}}
{{--    </div>--}}
{{--    @isset($fileDescriptionTypes)--}}
{{--        <div class="col-4 bg-grey-9 ml-2">--}}
{{--            @include('partials.overview._file-types')--}}
{{--        </div>--}}
{{--    @endisset--}}
{{--</div>--}}