@props(['authors', 'authorUsers' => null])

@if(!blank($authors))
    <div class="mb-3 mt-2">
        <label class="fw-semibold text-muted small text-uppercase" style="letter-spacing:.03em;">
            <i class="fas fa-users me-1"></i>Authors
        </label>
        <div class="d-flex flex-wrap gap-2 mt-1">
            @foreach($authors as $author)
                @php
                    $name        = $author['name'] ?? '';
                    $affiliation = trim($author['affiliations'] ?? '');
                    $mcUser      = $authorUsers ? ($authorUsers->get($name) ?? null) : null;
                @endphp
                @if($mcUser)
                    <a href="{{ route('public.authors.show', $mcUser) }}"
                       class="border rounded px-2 py-1 text-decoration-none bg-light d-inline-flex flex-column"
                       style="line-height:1.3;">
                        <span class="d-flex align-items-center gap-1">
                            <i class="fas fa-user-circle text-muted" style="font-size:.8rem;"></i>
                            <span class="fw-semibold text-dark" style="font-size:.82rem;">{{ $name }}</span>
                            <span class="badge text-bg-primary ms-1" style="font-size:.6rem; padding:.15em .3em;">MC</span>
                        </span>
                        @if($affiliation)
                            <span class="text-muted ms-3" style="font-size:.72rem;">{{ $affiliation }}</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('public.authors.search', ['search' => $name]) }}"
                       class="border rounded px-2 py-1 text-decoration-none bg-light d-inline-flex flex-column"
                       style="line-height:1.3;"
                       title="Search datasets by {{ $name }}">
                        <span class="d-flex align-items-center gap-1">
                            <i class="fas fa-user-circle text-muted" style="font-size:.8rem;"></i>
                            <span class="text-dark" style="font-size:.82rem;">{{ $name }}</span>
                            <i class="fas fa-search text-muted ms-1" style="font-size:.65rem;"></i>
                        </span>
                        @if($affiliation)
                            <span class="text-muted ms-3" style="font-size:.72rem;">{{ $affiliation }}</span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif
