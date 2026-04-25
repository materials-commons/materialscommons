{{-- ══ Dataset KPI strip — always visible ══════════════════════════════════════ --}}
<div class="row g-2 mb-4">
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Files</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($dataset->files_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ formatBytes($dataset->total_files_size) }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Samples</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($dataset->entities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in dataset</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Workflows</div>
            <div class="fw-bold fs-5 text-secondary">{{ number_format($dataset->workflows_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">documented</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Views</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($dataset->view_count ?? 0) }}</div>
            <div class="text-muted" style="font-size:.65rem;">total views</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Downloads</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($dataset->download_count ?? 0) }}</div>
            <div class="text-muted" style="font-size:.65rem;">total downloads</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Published</div>
            <div class="fw-bold text-muted" style="font-size:.8rem;">
                {{ $dataset->published_at ? $dataset->published_at->format('M j, Y') : '—' }}
            </div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ $dataset->published_at ? $dataset->published_at->diffForHumans() : 'not published' }}
            </div>
        </div>
    </div>
</div>

<form>
    <x-datasets.show-overview :dataset="$dataset">
        <div class="vr"></div>
        <div class="px-3 py-2">
            <div class="text-muted fw-semibold" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">Total Size</div>
            <div>{{ formatBytes($dataset->total_files_size) }}</div>
        </div>
    </x-datasets.show-overview>

    <x-datasets.show-authors :authors="$dataset->ds_authors"/>

    @if(!blank($dataset->description))
        <x-show-description :description="$dataset->description"/>
    @elseif (!blank($dataset->summary))
        <x-show-summary :summary="$dataset->summary"/>
    @endif

    <x-datasets.show-tags :tags="$dataset->tags"/>

    <x-datasets.show-citations :dataset="$dataset"/>

    <x-display-markdown-file :file="$readme"></x-display-markdown-file>

    <x-datasets.show-funding :dataset="$dataset"/>

    <x-datasets.show-papers-list :papers="$dataset->papers"/>

    @include('partials.overview._overview')

    <x-datasets.show-overview-files :dataset="$dataset"/>

</form>

