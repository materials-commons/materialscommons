@props([
    'row',
])

@php
    $community = $row['community'];
    $avatarColors = ['#0d6efd','#198754','#dc3545','#fd7e14','#6f42c1','#0dcaf0','#20c997','#d63384'];
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start gap-3 mb-3">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                 style="width:52px; height:52px;">
                <i class="fas fa-layer-group fa-lg text-primary"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div>
                        <h6 class="mb-1">
                            <a href="{{ $row['url'] }}" class="text-decoration-none">
                                {{ $row['name'] }}
                            </a>

                            @if($row['is_owner'])
                                <span class="badge text-bg-success ms-1" style="font-size:.65rem;">Organizer</span>
                            @endif
                        </h6>

                        <div class="text-muted" style="font-size:.82rem;">
                            <i class="fas fa-user-tie me-1"></i>
                            Organized by
                            @if($row['owner_url'])
                                <a href="{{ $row['owner_url'] }}" class="fw-semibold text-decoration-none">
                                    {{ $row['owner_name'] }}
                                </a>
                            @else
                                <span class="fw-semibold">{{ $row['owner_name'] ?? 'Unknown' }}</span>
                            @endif

                            @if(filled($row['owner_affiliations']))
                                <span class="ms-1">({{ $row['owner_affiliations'] }})</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-1">
                        <a href="{{ $row['url'] }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Open
                        </a>
                        <a href="{{ $row['datasets_url'] }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-database me-1"></i>Datasets
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(filled($row['summary']))
            <p class="text-muted mb-2" style="font-size:.9rem; line-height:1.5;">
                {{ \Illuminate\Support\Str::limit($row['summary'], 220) }}
            </p>
        @elseif(filled($row['description']))
            <p class="text-muted mb-2" style="font-size:.9rem; line-height:1.5;">
                {{ \Illuminate\Support\Str::limit($row['description'], 220) }}
            </p>
        @endif

        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="border rounded p-2 text-center">
                    <div class="fw-semibold text-primary">{{ number_format($row['dataset_count']) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">datasets</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border rounded p-2 text-center">
                    <div class="fw-semibold text-info">{{ number_format($row['views_count']) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">views</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border rounded p-2 text-center">
                    <div class="fw-semibold text-success">{{ number_format($row['downloads_count']) }}</div>
                    <div class="text-muted" style="font-size:.7rem;">downloads</div>
                </div>
            </div>
        </div>

        @if(!empty($row['tags']))
            <div class="mb-3">
                <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.7rem; letter-spacing:.04em;">
                    <i class="fas fa-tags me-1"></i>Topics
                </div>
                <div class="d-flex flex-wrap gap-1">
                    @foreach(array_slice($row['tags'], 0, 12, true) as $tag => $count)
                        <a href="{{ route('public.communities.search.tag', [$community, 'tag' => $tag]) }}"
                           class="badge text-bg-success text-decoration-none"
                           style="font-size:.75rem; font-weight:normal;">
                            {{ $tag }}
                            <span class="ms-1 opacity-75">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($row['contributors']))
            <div>
                <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.7rem; letter-spacing:.04em;">
                    <i class="fas fa-users me-1"></i>Contributors
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(array_slice($row['contributors'], 0, 8, true) as $name => $affiliation)
                        @php
                            $initials = collect(explode(' ', trim($name)))
                                ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                                ->take(2)
                                ->join('');
                            $color = $avatarColors[$loop->index % count($avatarColors)];
                        @endphp

                        <a href="{{ route('public.communities.search.author', [$community, 'author' => $name]) }}"
                           class="d-flex align-items-center gap-2 border rounded px-2 py-1 bg-light text-decoration-none text-dark"
                           style="max-width:220px;">
                            <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                                  style="width:28px; height:28px; font-size:.65rem; background:{{ $color }};">
                                {{ $initials }}
                            </span>
                            <span class="overflow-hidden">
                                <span class="d-block text-truncate fw-semibold" style="font-size:.78rem;">
                                    {{ $name }}
                                </span>
                                @if(filled($affiliation))
                                    <span class="d-block text-muted text-truncate" style="font-size:.68rem;">
                                        {{ $affiliation }}
                                    </span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
