@props([
    'dataset',
    'authorUsers' => null,
])

@php
    $communities = collect($dataset->publishedCommunities ?? collect());
@endphp

<section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
    <div class="card-body p-3 background-white">
        <div class="text-muted text-uppercase fw-semibold mb-3"
             style="font-size:.72rem; letter-spacing:.04em;">
            <i class="fas fa-info-circle me-1"></i>
            Dataset Details
        </div>

        <div class="d-flex flex-column gap-3" style="font-size:.84rem;">
            <div>
                <div class="text-muted" style="font-size:.7rem;">Publication status</div>
                @if($dataset->published_at)
                    <div class="fw-semibold text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Published
                    </div>
                @else
                    <div class="fw-semibold text-muted">
                        Not published
                    </div>
                @endif
            </div>

            <div>
                <div class="text-muted" style="font-size:.7rem;">Published date</div>
                <div class="fw-semibold">
                    {{ $dataset->published_at ? $dataset->published_at->format('M j, Y') : '—' }}
                </div>
            </div>

            <div>
                <div class="text-muted" style="font-size:.7rem;">License</div>
                <div class="fw-semibold">
                    {{ blank($dataset->license) ? '—' : $dataset->license }}
                </div>
            </div>

            <div>
                <div class="text-muted" style="font-size:.7rem;">Dataset ID</div>
                <div>
                    <code class="text-muted">{{ $dataset->id }}</code>
                </div>
            </div>

            @if(!blank($dataset->doi))
                <div>
                    <div class="text-muted" style="font-size:.7rem;">DOI</div>
                    <div>
                        <a href="{{ $dataset->doi }}" class="text-decoration-none">{{ $dataset->doi }}</a>
                    </div>
                </div>
            @endif

            <div>
                <div class="text-muted" style="font-size:.7rem;">Total size</div>
                <div class="fw-semibold">{{ formatBytes($dataset->total_files_size) }}</div>
            </div>
        </div>
    </div>
</section>

<section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
    <div class="card-body p-3 background-white">
        <x-datasets.show-authors :authors="$dataset->ds_authors" :author-users="$authorUsers"/>
    </div>
</section>

@if($communities->isNotEmpty())
    <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
        <div class="card-body p-3 background-white">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="text-muted text-uppercase fw-semibold"
                     style="font-size:.72rem; letter-spacing:.04em;">
                    <i class="fas fa-users me-1"></i>
                    Communities
                </div>
                <span class="badge rounded-pill bg-light text-muted border">
                    {{ number_format($communities->count()) }}
                </span>
            </div>

            <div class="d-flex flex-column gap-2">
                @foreach($communities->take(5) as $community)
                    <div class="p-2 rounded-3" style="background:#f8fafc;">
                        <div class="fw-semibold" style="font-size:.82rem;">
                            <a href="{{ route('public.communities.datasets.index', [$community]) }}"
                               class="text-decoration-none">
                                {{ $community->name }}
                            </a>
                        </div>
                        @if(!blank($community->summary))
                            <div class="text-muted" style="font-size:.72rem;">
                                {{ \Illuminate\Support\Str::limit($community->summary, 90) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
    <div class="card-body p-3 background-white">
        <x-datasets.show-tags :tags="$dataset->tags"/>
    </div>
</section>

<section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
    <div class="card-body p-3 background-white">
        <x-datasets.show-funding :dataset="$dataset"/>
    </div>
</section>

<section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
    <div class="card-body p-3 background-white">
        <x-datasets.show-papers-list :papers="$dataset->papers"/>
    </div>
</section>
