<div class="row">
    <div class="col mb-2">
        <div>
            <div class="row">
                <div class="col">
                    <x-datasets.show-download-links :dataset="$dataset"></x-datasets.show-download-links>
                </div>
            </div>
            <x-datasets.show-published-date :published="$dataset->published_at"/>
            <span class="ml-3 fs-9 grey-5">Views: {{$dataset->views_count}}</span>
            <span class="ml-3 fs-9 grey-5">Downloads: {{$dataset->downloads_count}}</span>
            <x-datasets.show-doi :doi="$dataset->doi"/>
            <x-datasets.show-license :dataset="$dataset"/>
            {{$slot ?? ''}}
        </div>
    </div>
</div>