@if(!blank($papers))
    <div class="mb-4">
        <div class="fw-semibold text-muted text-uppercase small mb-2">
            <i class="fas fa-book-open me-1"></i>Related Papers
        </div>

        <div class="d-flex flex-column gap-3">
            @foreach($papers as $paper)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        {{-- Title --}}
                        <div class="fw-semibold mb-2">
                            @if(!blank($paper->url))
                                <a href="{{ $paper->url }}" target="_blank" rel="noopener noreferrer"
                                   class="link-dark text-decoration-none">
                                    {{ $paper->name }}
                                    <i class="fas fa-external-link-alt ms-1 text-muted small"></i>
                                </a>
                            @else
                                {{ $paper->name }}
                            @endif
                        </div>

                        {{-- Reference / citation (URLs auto-linked) --}}
                        @if(!blank($paper->reference))
                            @php
                                $ref = e($paper->reference);
                                $ref = preg_replace(
                                    '~(https?://[^\s<>"]+[^\s<>".,;!\?)])~',
                                    '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-break">$1</a>',
                                    $ref
                                );
                            @endphp
                            <div class="text-muted small mb-3 lh-base">
                                {!! $ref !!}
                            </div>
                        @endif

                        {{-- DOI + URL badges --}}
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if(!blank($paper->doi))
                                <a href="https://doi.org/{{ $paper->doi }}" target="_blank" rel="noopener noreferrer"
                                   class="badge rounded-pill text-bg-primary text-decoration-none fw-normal">
                                    <i class="fas fa-link me-1"></i>DOI: {{ $paper->doi }}
                                </a>
                            @endif
                            @if(!blank($paper->url))
                                <a href="{{ $paper->url }}" target="_blank" rel="noopener noreferrer"
                                   class="badge rounded-pill text-bg-light border text-dark text-decoration-none fw-normal"
                                   title="{{ $paper->url }}">
                                    <i class="fas fa-external-link-alt me-1 text-muted"></i>View paper
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
