<table id="communities" class="table table-hover align-middle">
    <thead class="table-light">
    <tr>
        <th style="width:2rem;"></th>{{-- expand toggle --}}
        <th>Community</th>
        <th>Organizer</th>
        <th>Summary</th>
        <th>Datasets</th>
    </tr>
    </thead>
    <tbody>
    @php $maxDs = $communities->max('datasets_count') ?: 1; @endphp
    @foreach($communities as $community)
        @php
            $recentDs = $community->publishedDatasets->take(5)->map(fn($ds) => [
                'name' => $ds->name,
                'url'  => route('public.datasets.show', $ds),
            ])->values()->toArray();

            $detail = json_encode([
                'summary'  => $community->summary ?? '',
                'datasets' => $recentDs,
                'total'    => $community->datasets_count,
                'url'      => route('public.communities.datasets.index', $community),
            ], JSON_HEX_QUOT | JSON_HEX_TAG);
        @endphp
        <tr data-detail="{{ $detail }}">
            <td class="details-control text-center text-muted" style="cursor:pointer;">
                <i class="fas fa-chevron-right fa-fw" style="font-size:.75rem; transition:transform .15s;"></i>
            </td>
            <td>
                <a href="{{ route('public.communities.datasets.index', $community) }}" class="fw-semibold">
                    {{ $community->name }}
                </a>
            </td>
            <td class="text-muted small"><a href="{{route('public.authors.show', [$community->owner])}}">{{ $community->owner->name }}</a></td>
            <td class="text-muted small" style="max-width:320px;">
                {{ \Illuminate\Support\Str::limit($community->summary ?? '', 120) }}
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-primary">{{ number_format($community->datasets_count) }}</span>
                    <div class="flex-grow-1" style="max-width:80px;">
                        <div style="height:5px; border-radius:3px; background:#dee2e6;">
                            <div style="height:5px; border-radius:3px; background:#0d6efd;
                                        width:{{ round($community->datasets_count / $maxDs * 100) }}%;"></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            const table = $('#communities').DataTable({
                pageLength: 100,
                stateSave:  false,
                order:      [[4, 'desc']],
                columnDefs: [
                    {targets: [0], orderable: false, searchable: false},
                ],
            });

            // Child row toggle
            $('#communities tbody').on('click', 'td.details-control', function () {
                const td  = $(this);
                const tr  = td.closest('tr');
                const row = table.row(tr);
                const icon = td.find('i');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    icon.css('transform', 'rotate(0deg)');
                } else {
                    const detail = JSON.parse(tr.attr('data-detail'));
                    row.child(buildDetail(detail), 'p-0').show();
                    tr.addClass('shown');
                    icon.css('transform', 'rotate(90deg)');
                }
            });

            function buildDetail(d) {
                let html = '<div class="px-4 py-3 bg-light border-top">';

                if (d.summary) {
                    html += `<p class="mb-3 text-muted" style="font-size:.9rem;">${d.summary}</p>`;
                }

                if (d.datasets && d.datasets.length > 0) {
                    html += '<div class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">' +
                            '<i class="fas fa-database me-1"></i>Recent Datasets</div>';
                    html += '<div class="d-flex flex-wrap gap-2 mb-3">';
                    d.datasets.forEach(ds => {
                        html += `<a href="${ds.url}" class="badge text-bg-light border text-dark text-decoration-none"
                                    style="font-size:.78rem; font-weight:normal;">
                                    <i class="fas fa-database me-1 text-muted" style="font-size:.65rem;"></i>${ds.name}
                                 </a>`;
                    });
                    html += '</div>';
                }

                const shown = d.datasets ? d.datasets.length : 0;
                const more  = d.total - shown;
                html += `<a href="${d.url}" class="btn btn-sm btn-outline-primary">
                             Browse all ${d.total} dataset${d.total !== 1 ? 's' : ''}
                             ${more > 0 ? `<span class="badge text-bg-primary ms-1">+${more} more</span>` : ''}
                             <i class="fas fa-arrow-right ms-1"></i>
                         </a>`;

                html += '</div>';
                return html;
            }
        });
    </script>
@endpush
