<form>
    <x-datasets.show-overview :dataset="$dataset">
        <span class="ms-3 fs-10 grey-5">Size: {{formatBytes($dataset->total_files_size)}}</span>
    </x-datasets.show-overview>

    <x-datasets.show-citations :dataset="$dataset"/>

    <x-datasets.show-authors :authors="$dataset->ds_authors"/>

    <x-datasets.show-tags :tags="$dataset->tags"/>

    @if(!blank($dataset->description))
        <x-show-description :description="$dataset->description"/>
    @elseif (!blank($dataset->summary))
        <x-show-summary :summary="$dataset->summary"/>
    @endif

    <x-display-markdown-file :file="$readme"></x-display-markdown-file>

    <x-datasets.show-funding :dataset="$dataset"/>

    <x-datasets.show-papers-list :papers="$dataset->papers"/>

    @include('partials.overview._overview')

    <x-datasets.show-overview-files :dataset="$dataset"/>

</form>

