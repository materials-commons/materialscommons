@props([
    'coAuthors' => [],
    'coauthorCount' => 0,
])

<div class="tab-pane fade" id="tab-coauthors">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="card-title text-muted mb-1">
                        <i class="fas fa-users me-1"></i>Co-authors
                    </h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        People who appear with this author on published datasets.
                    </p>
                </div>

                <span class="badge text-bg-danger">
                    {{ number_format($coauthorCount) }}
                </span>
            </div>

            @if($coauthorCount === 0)
                <x-public.author.empty-state
                    icon="fas fa-users"
                    title="No co-authors"
                    message="No co-authors were found for this author."
                />
            @else
                @php
                    $maxC = $coAuthors ? max(array_column($coAuthors, 'count')) : 1;
                @endphp

                <div class="table-responsive">
                    <table id="coauthors-table" class="table table-hover align-middle mb-0" style="width:100%">
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
                                <td style="min-width:220px;">
                                    @if($data['user'])
                                        <a href="{{ route('public.authors.show', $data['user']) }}" class="fw-semibold text-decoration-none">
                                            {{ $name }}
                                        </a>
                                        <span class="badge text-bg-light border text-muted ms-1" style="font-size:.65rem;">
                                            <i class="fas fa-check me-1 text-success"></i>MC
                                        </span>
                                    @else
                                        <span class="fw-semibold">{{ $name }}</span>
                                    @endif
                                </td>

                                <td style="min-width:160px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge text-bg-primary">{{ $data['count'] }}</span>
                                        <div class="flex-grow-1" style="max-width:90px;">
                                            <div style="height:6px; border-radius:3px; background:#dee2e6;">
                                                <div style="height:6px; border-radius:3px; background:#0d6efd;
                                                        width:{{ round($data['count'] / $maxC * 100) }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td style="min-width:320px;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($data['datasets'] as $cDs)
                                            <a href="{{ route('public.datasets.show', $cDs) }}"
                                               class="badge text-bg-light border text-dark text-decoration-none"
                                               style="font-size:.72rem; font-weight:normal;">
                                                <i class="fas fa-database me-1 text-muted"
                                                   style="font-size:.65rem;"></i>{{ $cDs->name }}
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
