@props([
    'papers' => collect(),
    'paperCount' => 0,
])

<div class="tab-pane fade" id="tab-papers">
    @if($paperCount === 0)
        <p class="text-muted">No papers associated with this author's datasets.</p>
    @else
        <table id="papers-table" class="table table-hover align-middle" style="width:100%">
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
                    <td>
                        @if(!blank($paper->url))
                            <a href="{{ $paper->url }}" target="_blank">{{ $paper->name }}</a>
                        @elseif(!blank($paper->doi))
                            <a href="https://doi.org/{{ $paper->doi }}" target="_blank">{{ $paper->name }}</a>
                        @else
                            {{ $paper->name }}
                        @endif
                    </td>

                    <td>
                        @if(!blank($paper->doi))
                            <a href="https://doi.org/{{ $paper->doi }}" target="_blank"
                               class="text-muted small text-decoration-none">
                                <i class="fas fa-external-link-alt me-1"
                                   style="font-size:.7rem;"></i>{{ $paper->doi }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td class="text-muted small">{{ $paper->reference ?? '—' }}</td>

                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($pDatasets as $pDs)
                                <a href="{{ route('public.datasets.show', $pDs) }}">
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
    @endif
</div>
