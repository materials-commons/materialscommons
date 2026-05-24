@props([
    'papers' => collect(),
    'paperItems' => collect(),
    'papersMissingDoi' => collect(),
    'datasets' => collect(),
    'datasetsWithPapers' => collect(),
    'datasetsWithoutPapers' => collect(),
    'datasetsCitedByPapers' => collect(),
    'datasetsReadyToPublishButNotPublic' => collect(),
    'datasetsWithPublicationMetadataIncomplete' => collect(),
    'papersByProject' => collect(),
    'papersByOwner' => collect(),
])

@php
    $papers = collect($papers);
    $datasets = collect($datasets);
    $papersMissingDoi = collect($papersMissingDoi);
    $datasetsWithPapers = collect($datasetsWithPapers);
    $datasetsWithoutPapers = collect($datasetsWithoutPapers);
    $datasetsCitedByPapers = collect($datasetsCitedByPapers);
    $datasetsReadyToPublishButNotPublic = collect($datasetsReadyToPublishButNotPublic);
    $datasetsWithPublicationMetadataIncomplete = collect($datasetsWithPublicationMetadataIncomplete);
    $papersByProject = collect($papersByProject);
    $papersByOwner = collect($papersByOwner);

    $doiCoverage = $papers->count() === 0
        ? 0
        : round(($papers->count() - $papersMissingDoi->count()) / $papers->count() * 100);

    $datasetCitationCoverage = $datasets->count() === 0
        ? 0
        : round($datasetsWithPapers->count() / $datasets->count() * 100);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-bar me-1"></i>Publication Analytics
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Citation coverage, DOI readiness, publication metadata completeness, and setup ownership.
                </p>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">DOI Coverage</div>
                    <div class="fw-semibold">{{ $doiCoverage }}%</div>
                    <div class="progress mt-1" style="height:5px;">
                        <div class="progress-bar bg-success" style="width: {{ $doiCoverage }}%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Dataset Citation Coverage</div>
                    <div class="fw-semibold">{{ $datasetCitationCoverage }}%</div>
                    <div class="progress mt-1" style="height:5px;">
                        <div class="progress-bar bg-info" style="width: {{ $datasetCitationCoverage }}%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted" style="font-size:.75rem;">Projects With Papers</div>
                    <div class="fw-semibold">{{ number_format($papersByProject->count()) }}</div>
                    <div class="text-muted" style="font-size:.75rem;">from linked datasets</div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-12 col-md-6">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted mb-2" style="font-size:.78rem;">
                        <i class="fas fa-database me-1"></i>Dataset Publication Signals
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Datasets cited by papers</span>
                        <strong>{{ number_format($datasetsCitedByPapers->count()) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Datasets without associated paper</span>
                        <strong>{{ number_format($datasetsWithoutPapers->count()) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span>Ready to publish but not public</span>
                        <strong>{{ number_format($datasetsReadyToPublishButNotPublic->count()) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Publication metadata incomplete</span>
                        <strong>{{ number_format($datasetsWithPublicationMetadataIncomplete->count()) }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted mb-2" style="font-size:.78rem;">
                        <i class="fas fa-users me-1"></i>Papers by Setup User
                    </div>

                    @forelse($papersByOwner->take(6) as $owner => $count)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-truncate pe-2">{{ $owner }}</span>
                            <strong>{{ number_format($count) }}</strong>
                        </div>
                    @empty
                        <div class="text-muted">No paper setup data found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
