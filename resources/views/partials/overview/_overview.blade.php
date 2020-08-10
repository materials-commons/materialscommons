<h5>There are {{$objectCounts->filesCount}} files totalling {{formatBytes($totalFilesSize)}}.</h5>
<div class="row ml-1">
    <div class="col-4 bg-grey-9">
        @include('partials.overview._process-types')
    </div>
    <div class="col-3 bg-grey-9 ml-2">
        @include('partials.overview._object-types')
    </div>
    <div class="col-4 bg-grey-9 ml-2">
        @include('partials.overview._file-types')
    </div>
</div>