@props([
    'allTags' => collect(),
    'myTags' => collect(),
    'listedTags' => collect(),
    'sharedTags' => collect(),
    'myOnlyTags' => collect(),
    'taggedMyDatasetCount' => 0,
    'untaggedMyDatasetCount' => 0,
    'listedTaggedDatasetCount' => 0,
    'datasetsCount' => 0,
    'listedInDatasetsCount' => 0,
])

@php
    $allTags = collect($allTags);
    $myTags = collect($myTags);
    $listedTags = collect($listedTags);
    $sharedTags = collect($sharedTags);
    $myOnlyTags = collect($myOnlyTags);

    $mostUsed = $allTags->first();
    $mostViewed = $allTags->sortByDesc('views_count')->first();
    $mostDownloaded = $allTags->sortByDesc('downloads_count')->first();

    $myCoverage = $datasetsCount > 0 ? round(($taggedMyDatasetCount / $datasetsCount) * 100) : 0;
    $listedCoverage = $listedInDatasetsCount > 0 ? round(($listedTaggedDatasetCount / $listedInDatasetsCount) * 100) : 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-2">
            <i class="fas fa-chart-pie me-1"></i>Tag Analytics
        </h6>

        @if($allTags->isEmpty())
            <p class="text-muted mb-0">No tag analytics available yet.</p>
        @else
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">My dataset tag coverage</span>
                    <span class="fw-semibold small">{{ $myCoverage }}%</span>
                </div>
                <div class="progress" style="height:7px;">
                    <div class="progress-bar bg-success" style="width: {{ $myCoverage }}%;"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Listed-in dataset tag coverage</span>
                    <span class="fw-semibold small">{{ $listedCoverage }}%</span>
                </div>
                <div class="progress" style="height:7px;">
                    <div class="progress-bar bg-info" style="width: {{ $listedCoverage }}%;"></div>
                </div>
            </div>

            <div class="list-group list-group-flush">
                @if($mostUsed)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Most common tag
                        </div>
                        <a href="{{ route('public.tags.search', ['tag' => $mostUsed['tag']]) }}"
                           class="badge text-bg-success text-decoration-none">
                            {{ $mostUsed['tag'] }}
                            <span class="badge text-bg-light text-dark ms-1">{{ $mostUsed['count'] }}</span>
                        </a>
                    </div>
                @endif

                @if($mostViewed && $mostViewed['views_count'] > 0)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Highest view activity
                        </div>
                        <a href="{{ route('public.tags.search', ['tag' => $mostViewed['tag']]) }}"
                           class="badge text-bg-info text-decoration-none">
                            {{ $mostViewed['tag'] }}
                            <span class="badge text-bg-light text-dark ms-1">
                                {{ number_format($mostViewed['views_count']) }} views
                            </span>
                        </a>
                    </div>
                @endif

                @if($mostDownloaded && $mostDownloaded['downloads_count'] > 0)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Highest download activity
                        </div>
                        <a href="{{ route('public.tags.search', ['tag' => $mostDownloaded['tag']]) }}"
                           class="badge text-bg-warning text-decoration-none">
                            {{ $mostDownloaded['tag'] }}
                            <span class="badge text-bg-light text-dark ms-1">
                                {{ number_format($mostDownloaded['downloads_count']) }} downloads
                            </span>
                        </a>
                    </div>
                @endif
            </div>

            <div class="row g-2 mt-3">
                <div class="col-6">
                    <div class="border rounded p-2 h-100">
                        <div class="text-muted small">Shared vocabulary</div>
                        <div class="fw-semibold text-warning">{{ number_format($sharedTags->count()) }}</div>
                        <div class="text-muted" style="font-size:.7rem;">tags in both groups</div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="border rounded p-2 h-100">
                        <div class="text-muted small">My unique tags</div>
                        <div class="fw-semibold text-primary">{{ number_format($myOnlyTags->count()) }}</div>
                        <div class="text-muted" style="font-size:.7rem;">not in listed-in datasets</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
