<form>
    <x-datasets.show-overview :dataset="$dataset">
        <span class="ml-3 fs-9 grey-5">Size: {{formatBytes($dataset->total_files_size)}}</span>
    </x-datasets.show-overview>
    <x-datasets.show-authors :authors="$dataset->authors"/>
    <x-datasets.show-tags :tags="$dataset->tags"/>
    <x-show-summary :summary="$dataset->summary"/>
</form>
<hr/>
<br>