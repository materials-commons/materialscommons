<div class="mb-4">
    <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
        <i class="fas fa-book-open me-1"></i>Related Papers
    </div>
    @if(!blank($papers))
        <div class="d-flex flex-column gap-2">
            @foreach($papers as $paper)
                <div class="border rounded p-3 bg-light">
                    {{-- Title --}}
                    <div class="fw-semibold mb-1" style="font-size:.92rem; line-height:1.4;">
                        @if(!blank($paper->url))
                            <a href="{{ $paper->url }}" target="_blank" rel="noopener noreferrer"
                               class="text-decoration-none text-dark">
                                {{ $paper->name }}
                                <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size:.65rem;"></i>
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
                                '<a href="$1" target="_blank" rel="noopener noreferrer" style="word-break:break-all;">$1</a>',
                                $ref
                            );
                        @endphp
                        <div class="text-muted mb-2" style="font-size:.82rem; line-height:1.5;">
                            {!! $ref !!}
                        </div>
                    @endif

                    {{-- DOI + URL badges --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if(!blank($paper->doi))
                            <a href="https://doi.org/{{ $paper->doi }}" target="_blank" rel="noopener noreferrer"
                               class="badge text-bg-primary text-decoration-none"
                               style="font-size:.72rem; font-weight:normal;">
                                <i class="fas fa-link me-1"></i>DOI: {{ $paper->doi }}
                            </a>
                        @endif
                        @if(!blank($paper->url))
                            <a href="{{ $paper->url }}" target="_blank" rel="noopener noreferrer"
                               class="badge text-bg-light border text-dark text-decoration-none"
                               style="font-size:.72rem; font-weight:normal;"
                               title="{{ $paper->url }}">
                                <i class="fas fa-external-link-alt me-1 text-muted"></i>View paper
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="border rounded bg-light px-3 py-2" style="font-size:.92rem; line-height:1.6;">No papers for this dataset</div>
    @endif
</div>

