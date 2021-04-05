<form>
    <div class="row">
        <div class="col">
            <x-datasets.show-download-links :dataset="$dataset"></x-datasets.show-download-links>
        </div>
    </div>
    {{--    <x-datasets.show-overview :dataset="$dataset">--}}
    {{--        <span class="ml-3 fs-10 grey-5">Size: {{formatBytes($dataset->total_files_size)}}</span>--}}
    {{--    </x-datasets.show-overview>--}}
    {{--    <x-datasets.show-authors-short :authors="$dataset->ds_authors"/>--}}
    {{--    <x-datasets.show-tags :tags="$dataset->tags"/>--}}
    {{--    <x-show-summary :summary="$dataset->summary"/>--}}
</form>
<hr/>
