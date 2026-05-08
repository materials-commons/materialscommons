@props([
    'dataset',
])

@php
    $communities = collect($dataset->publishedCommunities ?? collect());
@endphp

@if($communities->isNotEmpty())
    <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="text-muted text-uppercase fw-semibold"
                         style="font-size:.72rem; letter-spacing:.04em;">
                        <i class="fas fa-users me-1"></i>
                        Communities
                    </div>
                    <h5 class="mb-0 mt-1">Where this dataset is listed</h5>
                </div>
                <a href="{{ route('public.datasets.communities.index', [$dataset]) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i>
                    View communities tab
                </a>
            </div>

            <div class="row g-3">
                @foreach($communities->take(3) as $community)
                    @php
                        $initials = collect(explode(' ', trim($community->name)))
                            ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                            ->take(2)
                            ->join('');
                    @endphp

                    <div class="col-12 col-md-4">
                        <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:34px;height:34px;background:#0d6efd;font-size:.7rem;">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:.84rem;">
                                        <a href="{{ route('public.communities.datasets.index', [$community]) }}"
                                           class="text-decoration-none">
                                            {{ $community->name }}
                                        </a>
                                    </div>
                                    <div class="text-muted" style="font-size:.7rem;">
                                        Community
                                    </div>
                                </div>
                            </div>

                            @if(!blank($community->summary))
                                <div class="text-muted mb-2" style="font-size:.74rem; line-height:1.45;">
                                    {{ \Illuminate\Support\Str::limit($community->summary, 120) }}
                                </div>
                            @elseif(!blank($community->description))
                                <div class="text-muted mb-2" style="font-size:.74rem; line-height:1.45;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($community->description), 120) }}
                                </div>
                            @endif

                            <span class="badge rounded-pill"
                                  style="background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">
                                Listed
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
