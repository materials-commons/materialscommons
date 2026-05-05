@props([
    'papers' => collect(),
    'paperCount' => 0,
])

<div class="tab-pane fade" id="tab-papers">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="card-title text-muted mb-1">
                        <i class="fas fa-file-alt me-1"></i>Papers
                    </h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        Papers associated with this author's datasets.
                    </p>
                </div>

                <span class="badge text-bg-secondary">
                    {{ number_format($paperCount) }}
                </span>
            </div>

            @if($paperCount === 0)
                <x-public.author.empty-state
                    icon="fas fa-file-alt"
                    title="No papers"
                    message="No papers are associated with this author's datasets."
                />
            @else
                <div class="table-responsive">
                    <table id="papers-table" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>DOI</th>
                            <th>Reference</th>
                            <th>Datasets</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($papers as $item)
                            @php
                                $paper = $item['paper'];
                                $pDatasets = $item['datasets'];
                            @endphp

                            <tr>
                                <td style="min-width:280px;">
                                    @if(!blank($paper->url))
                                        <a href="{{ $paper->url }}" target="_blank" rel="noopener" class="fw-semibold text-decoration-none">
                                            {{ $paper->name }}
                                        </a>
                                    @elseif(!blank($paper->doi))
                                        <a href="https://doi.org/{{ $paper->doi }}" target="_blank" rel="noopener" class="fw-semibold text-decoration-none">
                                            {{ $paper->name }}
                                        </a>
                                    @else
                                        <span class="fw-semibold">{{ $paper->name }}</span>
                                    @endif
                                </td>

                                <td>
                                    @if(!blank($paper->doi))
                                        <a href="https://doi.org/{{ $paper->doi }}" target="_blank"
                                           rel="noopener"
                                           class="text-muted small text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"
                                               style="font-size:.7rem;"></i>{{ $paper->doi }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-muted small" style="min-width:260px;">
                                    {{ $paper->reference ?? '—' }}
                                </td>

                                <td style="min-width:260px;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($pDatasets as $pDs)
                                            <a href="{{ route('public.datasets.show', $pDs) }}"
                                               class="badge text-bg-light border text-dark text-decoration-none"
                                               style="font-size:.72rem; font-weight:normal;">
                                                <i class="fas fa-database me-1 text-muted"
                                                   style="font-size:.65rem;"></i>{{ $pDs->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
