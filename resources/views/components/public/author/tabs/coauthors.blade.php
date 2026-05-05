@props([
    'coAuthors' => [],
    'coauthorCount' => 0,
])

<div class="tab-pane fade" id="tab-coauthors">
    @if($coauthorCount === 0)
        <p class="text-muted">No co-authors found.</p>
    @else
        @php
            $maxC = $coAuthors ? max(array_column($coAuthors, 'count')) : 1;
        @endphp

        <table id="coauthors-table" class="table table-hover align-middle" style="width:100%">
            <thead class="table-light">
            <tr>
                <th>Author</th>
                <th>Count</th>
                <th>Shared Datasets</th>
            </tr>
            </thead>

            <tbody>
            @foreach($coAuthors as $name => $data)
                <tr>
                    <td>
                        @if($data['user'])
                            <a href="{{ route('public.authors.show', $data['user']) }}" class="fw-semibold">{{ $name }}</a>
                            <span class="badge text-bg-light border text-muted ms-1" style="font-size:.65rem;">
                                <i class="fas fa-check me-1 text-success"></i>MC
                            </span>
                        @else
                            {{ $name }}
                        @endif
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-primary">{{ $data['count'] }}</span>
                            <div class="flex-grow-1" style="max-width:80px;">
                                <div style="height:6px; border-radius:3px; background:#dee2e6;">
                                    <div style="height:6px; border-radius:3px; background:#0d6efd;
                                            width:{{ round($data['count'] / $maxC * 100) }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($data['datasets'] as $cDs)
                                <a href="{{ route('public.datasets.show', $cDs) }}"
                                   class="badge text-bg-light border text-dark text-decoration-none"
                                   style="font-size:.72rem; font-weight:normal;">
                                    <i class="fas fa-database me-1 text-muted" style="font-size:.65rem;"></i>{{ $cDs->name }}
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
