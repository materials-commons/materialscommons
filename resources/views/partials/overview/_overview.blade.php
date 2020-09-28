<form>
    <x-datasets.show-overview :dataset="$dataset"/>

    <x-datasets.show-authors :authors="$dataset->authors"/>

    @if(!blank($dataset->description))
        <x-show-description :description="$dataset->description"/>
    @elseif (!blank($dataset->summary))
        <x-show-summary :summary="$dataset->summary"/>
    @endif

    <x-datasets.show-papers-list :papers="$dataset->papers"/>

    <x-datasets.show-tags :tags="$dataset->tags"/>
</form>
<hr>
<br>

@isset($objectCounts->filesCount)
    <h5>There are {{$objectCounts->filesCount}} files totalling {{formatBytes($totalFilesSize)}}.</h5>
@endisset
<div class="row ml-1">
    <div class="@isset($fileDescriptionTypes) col-4 @else col-5 @endisset bg-grey-9">
        @include('partials.overview._process-types')
    </div>
    <div class="@isset($fileDescriptionTypes) col-3 @else col-5 @endisset bg-grey-9 ml-2">
        @include('partials.overview._object-types')
    </div>
    @isset($fileDescriptionTypes)
        <div class="col-4 bg-grey-9 ml-2">
            @include('partials.overview._file-types')
        </div>
    @endisset
</div>