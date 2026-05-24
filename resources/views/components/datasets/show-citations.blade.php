<div class="mb-4">
    <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
        <i class="fas fa-quote-right me-1"></i>Citations
    </div>

    @if($hasCitations)
        <div class="d-flex flex-column gap-2">

            {{-- ── Dataset DOI citation ─────────────────────────────────────────── --}}
            @php
                $dsOk  = isset($citations->dataset->status) && $citations->dataset->status === 'ok';
                $dsMsg = $dsOk ? $citations->dataset->message : null;
            @endphp
            @if($dsMsg)
                @php
                    $dsRefCount = (int)($dsMsg->{"is-referenced-by-count"} ?? 0);
                    $dsTitle    = $dsMsg->title[0] ?? ($dataset->name ?? 'Dataset');
                    $dsDoi      = $dsMsg->DOI ?? null;
                    $dsUrl      = $dsMsg->URL ?? ($dsDoi ? 'https://doi.org/' . $dsDoi : null);
                    $dsAuthors  = isset($dsMsg->author)
                        ? collect($dsMsg->author)
                            ->map(fn($a) => trim(($a->given ?? '') . ' ' . ($a->family ?? '')))
                            ->filter()->join(', ')
                        : null;
                    $dsYear     = $dsMsg->published->{'date-parts'}[0][0]
                                  ?? $dsMsg->{'published-print'}->{'date-parts'}[0][0]
                                  ?? null;
                    $dsJournal  = $dsMsg->{'container-title'}[0] ?? null;
                @endphp
                <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="mb-1">
                                <span class="badge text-bg-primary" style="font-size:.68rem; font-weight:normal;">
                                    <i class="fas fa-database me-1"></i>Dataset
                                </span>
                            </div>
                            <div class="fw-semibold mb-1" style="font-size:.92rem; line-height:1.4;">
                                @if($dsUrl)
                                    <a href="{{ $dsUrl }}" target="_blank" rel="noopener noreferrer"
                                       class="text-decoration-none text-dark">
                                        {{ $dsTitle }}
                                        <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size:.65rem;"></i>
                                    </a>
                                @else
                                    {{ $dsTitle }}
                                @endif
                            </div>
                            @if($dsAuthors)
                                <div class="text-muted mb-1" style="font-size:.82rem;">{{ $dsAuthors }}</div>
                            @endif
                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                @if($dsJournal)
                                    <span class="text-muted fst-italic" style="font-size:.8rem;">{{ $dsJournal }}</span>
                                @endif
                                @if($dsYear)
                                    <span class="text-muted" style="font-size:.8rem;">{{ $dsYear }}</span>
                                @endif
                                @if($dsDoi)
                                    <a href="https://doi.org/{{ $dsDoi }}" target="_blank" rel="noopener noreferrer"
                                       class="badge text-bg-light border text-dark text-decoration-none"
                                       style="font-size:.70rem; font-weight:normal;">
                                        <i class="fas fa-link me-1 text-muted"></i>{{ $dsDoi }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-center" style="min-width:52px;">
                            <div class="fw-bold lh-1" style="font-size:1.4rem; color:#0d6efd;">
                                {{ number_format($dsRefCount) }}
                            </div>
                            <div class="text-muted" style="font-size:.65rem;">cited by</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-muted small fst-italic">Dataset citation data unavailable.</div>
            @endif

            {{-- ── Associated paper citations ───────────────────────────────────── --}}
            @foreach($citations->papers as $paper)
                @if(isset($paper->status) && $paper->status === 'ok')
                    @php
                        $msg      = $paper->message;
                        $refCount = (int)($msg->{"is-referenced-by-count"} ?? 0);
                        $title    = $msg->title[0] ?? 'Untitled';
                        $doi      = $msg->DOI ?? null;
                        $url      = $msg->URL ?? ($doi ? 'https://doi.org/' . $doi : null);
                        $authors  = isset($msg->author)
                            ? collect($msg->author)
                                ->map(fn($a) => trim(($a->given ?? '') . ' ' . ($a->family ?? '')))
                                ->filter()->join(', ')
                            : null;
                        $year     = $msg->published->{'date-parts'}[0][0]
                                    ?? $msg->{'published-print'}->{'date-parts'}[0][0]
                                    ?? null;
                        $journal  = $msg->{'container-title'}[0] ?? null;
                    @endphp
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="mb-1">
                                    <span class="badge text-bg-success" style="font-size:.68rem; font-weight:normal;">
                                        <i class="fas fa-file-alt me-1"></i>Paper
                                    </span>
                                </div>
                                <div class="fw-semibold mb-1" style="font-size:.92rem; line-height:1.4;">
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                           class="text-decoration-none text-dark">
                                            {{ $title }}
                                            <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size:.65rem;"></i>
                                        </a>
                                    @else
                                        {{ $title }}
                                    @endif
                                </div>
                                @if($authors)
                                    <div class="text-muted mb-1" style="font-size:.82rem;">{{ $authors }}</div>
                                @endif
                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                    @if($journal)
                                        <span class="text-muted fst-italic" style="font-size:.8rem;">{{ $journal }}</span>
                                    @endif
                                    @if($year)
                                        <span class="text-muted" style="font-size:.8rem;">{{ $year }}</span>
                                    @endif
                                    @if($doi)
                                        <a href="https://doi.org/{{ $doi }}" target="_blank" rel="noopener noreferrer"
                                           class="badge text-bg-light border text-dark text-decoration-none"
                                           style="font-size:.70rem; font-weight:normal;">
                                            <i class="fas fa-link me-1 text-muted"></i>{{ $doi }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-center" style="min-width:52px;">
                                <div class="fw-bold lh-1" style="font-size:1.4rem; color:#198754;">
                                    {{ number_format($refCount) }}
                                </div>
                                <div class="text-muted" style="font-size:.65rem;">cited by</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    @else
        <p class="text-muted small fst-italic mb-0">No citation data found for this dataset or its associated papers.</p>
    @endif
</div>
