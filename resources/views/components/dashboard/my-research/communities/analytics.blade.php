@props([
    'communityRows' => collect(),
    'ownedCommunities' => collect(),
    'appearsInCommunities' => collect(),
    'publicCommunities' => collect(),
    'topTopics' => collect(),
    'topContributors' => collect(),
])

@php
    $communityRows = collect($communityRows);
    $ownedCommunities = collect($ownedCommunities);
    $appearsInCommunities = collect($appearsInCommunities);
    $publicCommunities = collect($publicCommunities);
    $topTopics = collect($topTopics);
    $topContributors = collect($topContributors);

    $strongest = $communityRows->sortByDesc('score')->first();
    $mostDatasets = $communityRows->sortByDesc('dataset_count')->first();
    $mostEngaged = $communityRows->sortByDesc(fn($row) => $row['views_count'] + $row['downloads_count'])->first();

    $ownedPct = $communityRows->count() > 0
        ? round(($ownedCommunities->count() / $communityRows->count()) * 100)
        : 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-2">
            <i class="fas fa-chart-pie me-1"></i>Community Analytics
        </h6>

        @if($communityRows->isEmpty())
            <p class="text-muted mb-0">No community analytics available yet.</p>
        @else
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Communities you organize</span>
                    <span class="fw-semibold small">{{ $ownedPct }}%</span>
                </div>
                <div class="progress" style="height:7px;">
                    <div class="progress-bar bg-success" style="width: {{ $ownedPct }}%;"></div>
                </div>
            </div>

            <div class="list-group list-group-flush">
                @if($strongest)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Strongest overall community
                        </div>
                        <a href="{{ $strongest['url'] }}" class="fw-semibold text-decoration-none">
                            {{ $strongest['name'] }}
                        </a>
                        <div class="text-muted small">
                            {{ number_format($strongest['dataset_count']) }} datasets,
                            {{ number_format($strongest['views_count']) }} views,
                            {{ number_format($strongest['downloads_count']) }} downloads
                        </div>
                    </div>
                @endif

                @if($mostDatasets)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Largest dataset collection
                        </div>
                        <a href="{{ $mostDatasets['datasets_url'] }}" class="fw-semibold text-decoration-none">
                            {{ $mostDatasets['name'] }}
                        </a>
                        <div class="text-muted small">
                            {{ number_format($mostDatasets['dataset_count']) }} published datasets
                        </div>
                    </div>
                @endif

                @if($mostEngaged)
                    <div class="list-group-item px-0">
                        <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                            Highest engagement
                        </div>
                        <a href="{{ $mostEngaged['url'] }}" class="fw-semibold text-decoration-none">
                            {{ $mostEngaged['name'] }}
                        </a>
                        <div class="text-muted small">
                            {{ number_format($mostEngaged['views_count'] + $mostEngaged['downloads_count']) }}
                            combined views/downloads
                        </div>
                    </div>
                @endif
            </div>

            @if($topTopics->isNotEmpty())
                <div class="mt-3">
                    <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                        <i class="fas fa-tags me-1"></i>Top Topics
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($topTopics->take(8) as $topic)
                            <span class="badge text-bg-success" style="font-weight:normal;">
                                {{ $topic['tag'] }}
                                <span class="ms-1 opacity-75">{{ $topic['count'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($topContributors->isNotEmpty())
                <div class="mt-3">
                    <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                        <i class="fas fa-users me-1"></i>Frequent Contributors
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($topContributors->take(8) as $contributor)
                            <span class="badge text-bg-light border text-dark" style="font-weight:normal;">
                                {{ $contributor['name'] }}
                                <span class="text-muted ms-1">({{ $contributor['community_count'] }})</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
