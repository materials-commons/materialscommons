@props([
    'ownedTags' => [],
    'includedTags' => [],
])

@php
    $hasTags = count($ownedTags) + count($includedTags) > 0;
@endphp

<div class="tab-pane fade" id="tab-tags">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="card-title text-muted mb-1">
                        <i class="fas fa-tags me-1"></i>Tags
                    </h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        Tags used across owned datasets and datasets where this author is listed.
                    </p>
                </div>

                <span class="badge text-bg-success">
                    {{ number_format(count($ownedTags) + count($includedTags)) }}
                </span>
            </div>

            @if(!$hasTags)
                <x-public.author.empty-state
                    icon="fas fa-tags"
                    title="No tags"
                    message="No tags were found across this author's datasets."
                />
            @else
                <div class="row g-4">
                    @if(count($ownedTags) > 0)
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-semibold text-muted text-uppercase mb-2"
                                    style="font-size:.78rem; letter-spacing:.04em;">
                                    <i class="fas fa-database me-1"></i>In Owned Datasets
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
                        </div>
                    @endif

                    @if(count($includedTags) > 0)
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-semibold text-muted text-uppercase mb-2"
                                    style="font-size:.78rem; letter-spacing:.04em;">
                                    <i class="fas fa-list me-1"></i>In Included Datasets
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
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
