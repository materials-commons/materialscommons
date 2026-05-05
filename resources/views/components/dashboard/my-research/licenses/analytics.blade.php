{{-- resources/views/components/dashboard/my-research/licenses/analytics.blade.php --}}
@props([
    'datasets' => collect(),
    'publishedDatasets' => collect(),
    'draftDatasets' => collect(),
    'missingLicense' => collect(),
    'customOrUnknownLicense' => collect(),
    'publicMissingLicense' => collect(),
    'draftMissingLicense' => collect(),
    'licenseDistribution' => collect(),
])

@php
    $datasets = collect($datasets);
    $publishedDatasets = collect($publishedDatasets);
    $draftDatasets = collect($draftDatasets);
    $missingLicense = collect($missingLicense);
    $customOrUnknownLicense = collect($customOrUnknownLicense);
    $publicMissingLicense = collect($publicMissingLicense);
    $draftMissingLicense = collect($draftMissingLicense);
    $licenseDistribution = collect($licenseDistribution);

    $licensedDatasets = $datasets->reject(fn($dataset) => blank($dataset->license ?? null));

    $coverageRate = $datasets->count() === 0
        ? 0
        : round($licensedDatasets->count() / $datasets->count() * 100);

    $publicCoverageRate = $publishedDatasets->count() === 0
        ? 0
        : round(($publishedDatasets->count() - $publicMissingLicense->count()) / $publishedDatasets->count() * 100);

    $draftCoverageRate = $draftDatasets->count() === 0
        ? 0
        : round(($draftDatasets->count() - $draftMissingLicense->count()) / $draftDatasets->count() * 100);

    $mostUsedLicense = $licenseDistribution
        ->reject(fn($count, $license) => $license === 'Missing License')
        ->keys()
        ->first() ?? '—';
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-chart-line me-1"></i>License Analytics
        </h6>

        <div class="row g-2">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Coverage</div>
                    <div class="fw-semibold fs-5">{{ $coverageRate }}%</div>
                    <div class="text-muted" style="font-size:.7rem;">datasets with license</div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Public Coverage</div>
                    <div class="fw-semibold fs-5 text-success">{{ $publicCoverageRate }}%</div>
                    <div class="text-muted" style="font-size:.7rem;">published datasets</div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Draft Coverage</div>
                    <div class="fw-semibold fs-5 text-secondary">{{ $draftCoverageRate }}%</div>
                    <div class="text-muted" style="font-size:.7rem;">draft datasets</div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Missing</div>
                    <div class="fw-semibold fs-5 text-danger">{{ number_format($missingLicense->count()) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">needs license</div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Needs Review</div>
                    <div class="fw-semibold fs-5 text-warning">{{ number_format($customOrUnknownLicense->count()) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">custom / unknown</div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Most Used</div>
                    <div class="fw-semibold text-truncate" title="{{ $mostUsedLicense }}">{{ $mostUsedLicense }}</div>
                    <div class="text-muted" style="font-size:.7rem;">licensed datasets</div>
                </div>
            </div>
        </div>
    </div>
</div>
