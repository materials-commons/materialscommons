@props(['dataset'])

@php
    $doi = !blank($dataset->doi) ? $dataset->doi : $dataset->test_doi;
@endphp

@if(!blank($doi))
    <div class="vr d-none d-md-block"></div>
    <div class="px-3 py-2 bg-body-tertiary border rounded-3">
        <div class="text-muted fw-semibold small text-uppercase">DOI</div>
        <div class="fw-semibold text-break">
            <a href="https://doi.org/{{Illuminate\Support\Str::of($doi)->after('doi:')->trim()}}"
               target="_blank"
               class="link-primary text-decoration-none">
                <i class="fas fa-external-link-alt me-1 text-muted"></i>{{ $doi }}
            </a>
        </div>
    </div>
@endif
