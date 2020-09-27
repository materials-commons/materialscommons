@component('components.item-details', ['item' => $dataset])
    @slot('top')
        <x-datasets.show-authors :authors="$dataset->authors"></x-datasets.show-authors>
    @endslot

    @slot('slotStart')
        <div class="row">
            <div class="col">
                <x-datasets.show-download-links :dataset="$dataset"></x-datasets.show-download-links>
            </div>
        </div>
    @endslot

    <span class="ml-3 fs-9 grey-5">Published:
                    @isset($dataset->published_at)
            {{$dataset->published_at->diffForHumans()}}
        @else
            Not Published
        @endisset
                </span>

    <span class="ml-3 fs-9 grey-5">Views: {{$dataset->views_count}}</span>
    <span class="ml-3 fs-9 grey-5">Downloads: {{$dataset->downloads_count}}</span>
    <x-datasets.show-doi :doi="$dataset->doi"></x-datasets.show-doi>
    <x-datasets.show-license :license="$dataset->license"
                             :license-link="$dataset->license_link"></x-datasets.show-license>

    @slot('bottom')
        <x-datasets.show-papers-list :papers="$dataset->papers"></x-datasets.show-papers-list>

        <x-datasets.show-tags :tags="$dataset->tags"></x-datasets.show-tags>
    @endslot
@endcomponent

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