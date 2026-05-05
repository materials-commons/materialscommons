@props([
    'user',
    'ownedCount' => 0,
    'includedCount' => 0,
])

@php
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn($part) => mb_substr($part, 0, 1))
        ->implode('');

    $initials = filled($initials) ? mb_strtoupper($initials) : '?';
@endphp

<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div class="card-body p-0 background-white">
        <div class="p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row gap-3 gap-md-4 align-items-start">
                <div
                    class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 border border-primary border-opacity-25 text-primary fw-semibold"
                    style="width:88px; height:88px; font-size:1.6rem;">
                    {{ $initials }}
                </div>

                <div class="flex-grow-1 min-width-0">
                    <div class="text-muted text-uppercase mb-1" style="font-size:.72rem; letter-spacing:.06em;">
                        Author Profile
                    </div>

                    <div class="d-flex flex-column flex-lg-row align-items-start justify-content-between gap-3">
                        <div class="min-width-0">
                            <h3 class="mb-2 text-truncate">{{ $user->name }}</h3>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if(!blank($user->affiliations))
                                    <span class="badge text-bg-light border text-muted px-2 py-1"
                                          title="{{ $user->affiliations }}">
                                        <i class="fas fa-building me-1"></i>{{ $user->affiliations }}
                                    </span>
                                @endif

                                @if(!blank($user->orcid))
                                    <a href="https://orcid.org/{{ $user->orcid }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="badge text-bg-light border text-muted text-decoration-none px-2 py-1">
                                        <i class="fas fa-id-badge me-1"></i>ORCID
                                    </a>
                                @endif

                                @if(!blank($user->homepage_url))
                                    <a href="{{ $user->homepage_url }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="badge text-bg-light border text-muted text-decoration-none px-2 py-1">
                                        <i class="fas fa-globe me-1"></i>Homepage
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-shrink-0">
                            <div class="border rounded px-3 py-2 text-center bg-light">
                                <div class="fw-semibold text-primary">{{ number_format($ownedCount) }}</div>
                                <div class="text-muted" style="font-size:.72rem;">Owned</div>
                            </div>

                            <div class="border rounded px-3 py-2 text-center bg-light">
                                <div class="fw-semibold text-info">{{ number_format($includedCount) }}</div>
                                <div class="text-muted" style="font-size:.72rem;">Included</div>
                            </div>
                        </div>
                    </div>

                    @if(!blank($user->description))
                        <div class="border rounded bg-light p-3 mt-1">
                            <p class="text-muted mb-0" style="font-size:.92rem; line-height:1.55;">
                                {{ $user->description }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
