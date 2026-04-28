<x-datasets.show-download-links :dataset="$dataset"></x-datasets.show-download-links>

{{-- ── Horizontal metadata strip ── --}}
<div class="d-flex flex-wrap align-items-stretch border rounded mt-3 bg-light overflow-hidden">

    <x-datasets.show-published-date :dataset="$dataset"/>

    @if(!is_null($dataset->published_at) || !is_null($dataset->test_published_at))
        <div class="vr"></div>
        <div class="px-3 py-2 text-center">
            <div class="text-muted fw-semibold" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">Views</div>
            <div class="fw-bold text-success">{{ number_format($dataset->views_count) }}</div>
        </div>
        <div class="vr"></div>
        <div class="px-3 py-2 text-center">
            <div class="text-muted fw-semibold" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">Downloads</div>
            <div class="fw-bold text-primary">{{ number_format($dataset->downloads_count) }}</div>
        </div>
    @endif

    <x-datasets.show-doi :dataset="$dataset"/>

    <x-datasets.show-license :dataset="$dataset"/>

    {{-- slot: e.g. Size from overview-tab --}}
    {{ $slot ?? '' }}

</div>
