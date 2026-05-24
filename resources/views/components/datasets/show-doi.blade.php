@props(['dataset'])

@php
    $doi = !blank($dataset->doi) ? $dataset->doi : $dataset->test_doi;
@endphp

@if(!blank($doi))
    <div class="vr"></div>
    <div class="px-3 py-2">
        <div class="text-muted fw-semibold" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">DOI</div>
        <div>
            <a href="https://doi.org/{{Illuminate\Support\Str::of($doi)->after('doi:')->trim()}}"
               target="_blank" class="text-decoration-none">
                <i class="fas fa-external-link-alt me-1 text-muted" style="font-size:.75rem;"></i>{{ $doi }}
            </a>
        </div>
    </div>
@endif
