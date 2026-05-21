@props([
    'datasets' => collect(),
    'publishedDatasets' => collect(),
    'draftDatasets' => collect(),
    'missingLicense' => collect(),
    'missingDoi' => collect(),
    'needsMetadata' => collect(),
    'publicEngagementDatasets' => collect(),
])

@php
    $datasets = collect($datasets);
    $publishedDatasets = collect($publishedDatasets);
    $draftDatasets = collect($draftDatasets);
    $missingLicense = collect($missingLicense);
    $missingDoi = collect($missingDoi);
    $needsMetadata = collect($needsMetadata);
    $publicEngagementDatasets = collect($publicEngagementDatasets);

    $totalViews = $publishedDatasets->sum('views_count');
    $totalDownloads = $datasets->sum(fn($dataset) => (int) ($dataset->downloads_count ?? 0));
    $publishedWithNoEngagement = $publishedDatasets->filter(function ($dataset) {
        return (int) ($dataset->views_count ?? 0) === 0
            && (int) ($dataset->downloads_count ?? 0) === 0;
    });

    $engagementRate = $publishedDatasets->count() === 0
        ? 0
        : round($publicEngagementDatasets->count() / $publishedDatasets->count() * 100);

    $metadataReady = $datasets->count() === 0
        ? 0
        : round(($datasets->count() - $needsMetadata->count()) / $datasets->count() * 100);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-chart-line me-1"></i>Dataset Analytics
        </h6>

        <div class="row g-2">
            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Total Views</div>
                    <div class="fw-semibold fs-5">{{ number_format($totalViews) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">across your datasets</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Total Downloads</div>
                    <div class="fw-semibold fs-5">{{ number_format($totalDownloads) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">public download activity</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Engagement Rate</div>
                    <div class="fw-semibold fs-5">{{ $engagementRate }}%</div>
                    <div class="text-muted" style="font-size:.7rem;">published with views/downloads</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Metadata Ready</div>
                    <div class="fw-semibold fs-5">{{ $metadataReady }}%</div>
                    <div class="text-muted" style="font-size:.7rem;">core fields complete</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Missing License</div>
                    <div class="fw-semibold fs-5 text-danger">{{ number_format($missingLicense->count()) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">license needed</div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">No Engagement</div>
                    <div class="fw-semibold fs-5 text-secondary">{{ number_format($publishedWithNoEngagement->count()) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">published but inactive</div>
                </div>
            </div>
        </div>
    </div>
</div>
