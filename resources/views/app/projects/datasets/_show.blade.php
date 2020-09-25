@component('components.item-details', ['item' => $dataset])
    @slot('top')
        <x-datasets.show-authors :authors="$dataset->authors"></x-datasets.show-authors>
    @endslot


    <span class="ml-4">Published:
        @isset($dataset->published_at)
            {{$dataset->published_at->diffForHumans()}}
        @else
            Not Published
        @endisset
    </span>

    <x-datasets.show-doi :doi="$dataset->doi"></x-datasets.show-doi>
    <x-datasets.show-license :license="$dataset->license"
                             :license-link="$dataset->license_link"></x-datasets.show-license>

    @slot('bottom')
        <x-datasets.show-papers-list :papers="$dataset->papers"></x-datasets.show-papers-list>

        <x-datasets.show-tags :tags="$dataset->tags"></x-datasets.show-tags>
    @endslot
@endcomponent