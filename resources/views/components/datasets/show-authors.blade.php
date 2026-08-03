@props(['authors', 'authorUsers' => null])

@if(!blank($authors))
    <div class="mb-4 mt-3">
        <div class="fw-semibold text-muted small text-uppercase mb-2">
            <i class="fas fa-users me-1"></i>Authors
        </div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($authors as $author)
                @php
                    $name        = $author['name'] ?? '';
                    $affiliation = trim($author['affiliations'] ?? '');
                    $mcUser      = $authorUsers ? ($authorUsers->get($name) ?? null) : null;
                @endphp
                @if($mcUser)
                    <a href="{{ route('public.authors.show', $mcUser) }}"
                       class="card card-body border-0 shadow-sm py-2 px-3 text-decoration-none bg-body-tertiary">
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill text-bg-primary">
                                <i class="fas fa-user-check me-1"></i>MC
                            </span>
                            <span class="fw-semibold text-dark">{{ $name }}</span>
                        </span>
                        @if($affiliation)
                            <span class="text-muted small mt-1">
                                <i class="fas fa-building me-1"></i>{{ $affiliation }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('public.authors.search', ['search' => $name]) }}"
                       class="card card-body border shadow-sm py-2 px-3 text-decoration-none bg-white"
                       title="Search datasets by {{ $name }}">
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill text-bg-light border text-muted">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="fw-semibold text-dark">{{ $name }}</span>
                            <i class="fas fa-search text-muted small ms-auto"></i>
                        </span>
                        @if($affiliation)
                            <span class="text-muted small mt-1">
                                <i class="fas fa-building me-1"></i>{{ $affiliation }}
                            </span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif
