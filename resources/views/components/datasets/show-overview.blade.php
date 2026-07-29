<x-datasets.show-download-links :dataset="$dataset"></x-datasets.show-download-links>

{{-- ── Horizontal metadata strip ── --}}
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-2">
        <div class="d-flex flex-wrap align-items-stretch gap-2">

            <x-datasets.show-published-date :dataset="$dataset"/>

            @if(!is_null($dataset->published_at) || !is_null($dataset->test_published_at))
                <div class="vr d-none d-md-block"></div>
                <div class="px-3 py-2 text-center bg-body-tertiary border rounded-3">
                    <div class="text-muted fw-semibold small text-uppercase">Views</div>
                    <div class="fw-bold fs-5 text-success">{{ number_format($dataset->views_count) }}</div>
                </div>
                <div class="vr d-none d-md-block"></div>
                <div class="px-3 py-2 text-center bg-body-tertiary border rounded-3">
                    <div class="text-muted fw-semibold small text-uppercase">Downloads</div>
                    <div class="fw-bold fs-5 text-primary">{{ number_format($dataset->downloads_count) }}</div>
                </div>
            @endif

            <x-datasets.show-doi :dataset="$dataset"/>

            <x-datasets.show-license :dataset="$dataset"/>

            {{-- slot: e.g. Size from overview-tab --}}
            {{ $slot ?? '' }}

        </div>
    </div>
</div>
