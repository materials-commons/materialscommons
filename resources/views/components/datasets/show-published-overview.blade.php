@component('components.item-details', ['item' => $dataset, 'noDescription' => true])
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

    <span class="ml-4 fs-9 grey-5">Published:
                    @isset($dataset->published_at)
            {{$dataset->published_at->diffForHumans()}}
        @else
            Not Published
        @endisset
                </span>

    <span class="ml-4 fs-9 grey-5">Views: {{$dataset->views_count}}</span>
    <span class="ml-4 fs-9 grey-5">Downloads: {{$dataset->downloads_count}}</span>
    <x-datasets.show-doi :doi="$dataset->doi"></x-datasets.show-doi>
    <x-datasets.show-license :license="$dataset->license"
                             :license-link="$dataset->license_link"></x-datasets.show-license>
@endcomponent