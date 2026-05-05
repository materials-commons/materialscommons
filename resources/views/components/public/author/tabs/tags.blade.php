@props([
    'ownedTags' => [],
    'includedTags' => [],
])

@php
    $hasTags = count($ownedTags) + count($includedTags) > 0;
@endphp

<div class="tab-pane fade" id="tab-tags">
    @if(!$hasTags)
        <p class="text-muted">No tags found across this author's datasets.</p>
    @else
        <div class="row g-4">
            @if(count($ownedTags) > 0)
                <div class="col-12 col-md-6">
                    <h6 class="fw-semibold text-muted text-uppercase mb-2"
                        style="font-size:.78rem; letter-spacing:.04em;">
                        <i class="fas fa-database me-1"></i>In My Datasets
                    </h6>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($ownedTags as $tag => $count)
                            <a href="{{ route('public.tags.search', ['tag' => $tag]) }}"
                               class="badge text-bg-success text-decoration-none px-2 py-1"
                               style="font-size:.82rem; font-weight:normal;">
                                {{ $tag }}
                                <span class="badge text-bg-light text-dark ms-1"
                                      style="font-size:.7rem;">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($includedTags) > 0)
                <div class="col-12 col-md-6">
                    <h6 class="fw-semibold text-muted text-uppercase mb-2"
                        style="font-size:.78rem; letter-spacing:.04em;">
                        <i class="fas fa-list me-1"></i>In Datasets I'm Listed In
                    </h6>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($includedTags as $tag => $count)
                            <a href="{{ route('public.tags.search', ['tag' => $tag]) }}"
                               class="badge text-bg-info text-decoration-none px-2 py-1"
                               style="font-size:.82rem; font-weight:normal;">
                                {{ $tag }}
                                <span class="badge text-bg-light text-dark ms-1"
                                      style="font-size:.7rem;">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
