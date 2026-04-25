{{--
  Prototype v2 Details tab.
  To try it: in show.blade.php change
      @include('app.projects.tabs.overview')
  to
      @include('app.projects.tabs.overview-v2')

  Philosophy:
    The counts (files, studies, samples, datasets) are already shown as KPI
    cards on the Home tab, so they are not repeated here.  The two things this
    tab uniquely has are:
      • $fileDescriptionTypes  — file extension => count
      • $activitiesGroup       — process/activity name => count
    Both are currently buried as inline tag lists. This version surfaces them
    as Plotly charts so they are immediately scannable.

    Metadata (owner, dates, slug, description) is kept in a compact info strip
    rather than a form, since it is read-only for most visitors.
--}}

{{-- ══════════════════════════════════════════════════════════════════════════
     Metadata strip
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2 px-3">
        <div class="d-flex flex-wrap gap-3 align-items-center" style="font-size:.82rem;">

            <span class="text-muted">
                <i class="fas fa-user fa-fw me-1"></i>
                <strong>Owner:</strong> {{ $project->owner->name }}
            </span>

            <span class="text-muted">
                <i class="fas fa-users fa-fw me-1"></i>
                <a href="{{route('projects.users.index', [$project])}}" class="text-muted text-decoration-none">
                    {{ $project->team->members->count() }} member(s),
                    {{ $project->team->admins->count() }} admin(s)
                </a>
            </span>

            <span class="text-muted"
                  data-bs-toggle="tooltip"
                  title="{{ $project->updated_at->format('M j, Y g:i a') }}">
                <i class="far fa-clock fa-fw me-1"></i>
                <strong>Updated:</strong> {{ $project->updated_at->diffForHumans() }}
            </span>

            <span class="text-muted">
                <i class="fas fa-hdd fa-fw me-1"></i>
                <strong>Size:</strong> {{ formatBytes($project->size) }}
            </span>

            <span class="text-muted">
                <i class="fas fa-tag fa-fw me-1"></i>
                <strong>Slug:</strong> <code class="text-muted">{{ $project->slug }}</code>
            </span>

            <span class="text-muted">
                <i class="fas fa-fingerprint fa-fw me-1"></i>
                <strong>ID:</strong> {{ $project->id }}
            </span>

        </div>

        {{-- Description / summary if present --}}
        @if(isset($project->description) && !blank($project->description))
            <hr class="my-2">
            <p class="mb-0 text-muted" style="font-size:.85rem;">{{ $project->description }}</p>
        @elseif(isset($project->summary) && !blank($project->summary))
            <hr class="my-2">
            <p class="mb-0 text-muted" style="font-size:.85rem;">{{ $project->summary }}</p>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     Charts — File Types & Process Types
     ══════════════════════════════════════════════════════════════════════════ --}}
@php
    $hasFileTypes    = isset($fileDescriptionTypes) && count($fileDescriptionTypes) > 0;
    $hasProcessTypes = isset($activitiesGroup)       && count($activitiesGroup)       > 0;
@endphp

@if($hasFileTypes || $hasProcessTypes)
<div class="row g-3 mb-4">

    {{-- File Types chart --}}
    @if($hasFileTypes)
    @php
        // Sort descending by count, take top 20 so the chart doesn't get unreadable.
        arsort($fileDescriptionTypes);
        $ftTypes  = array_keys(array_slice($fileDescriptionTypes, 0, 20, true));
        $ftCounts = array_values(array_slice($fileDescriptionTypes, 0, 20, true));
        $ftTotal  = array_sum($fileDescriptionTypes);
        $ftShown  = count($ftTypes);
        $ftMore   = count($fileDescriptionTypes) - $ftShown; // how many were trimmed
    @endphp
    <div class="col-12 {{ $hasProcessTypes ? 'col-md-7' : '' }}">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-file-alt me-1"></i> File Types
                    <span class="badge bg-light text-secondary ms-1">{{ number_format($ftTotal) }} files</span>
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Distribution across {{ count($fileDescriptionTypes) }} extension(s)
                    @if($ftMore > 0)
                        &nbsp;· showing top {{ $ftShown }}, {{ $ftMore }} more not shown
                    @endif
                </p>
                <div id="chart-file-types" style="height:{{ min(60 + $ftShown * 28, 480) }}px;"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Process Types chart --}}
    @if($hasProcessTypes)
    @php
        // Sort by count descending, cap at 20.
        $agSorted = collect($activitiesGroup)->sortByDesc('count')->take(20)->values();
        $agNames  = $agSorted->pluck('name')->map(fn($n) => strlen($n) > 30 ? substr($n,0,28).'…' : $n)->values()->toArray();
        $agCounts = $agSorted->pluck('count')->values()->toArray();
        $agTotal  = collect($activitiesGroup)->sum('count');
        $agMore   = count($activitiesGroup) - count($agNames);
    @endphp
    <div class="col-12 {{ $hasFileTypes ? 'col-md-5' : '' }}">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-cogs me-1"></i> Process Types
                    <span class="badge bg-light text-secondary ms-1">{{ number_format($agTotal) }} total</span>
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    {{ count($activitiesGroup) }} distinct process type(s)
                    @if($agMore > 0)
                        &nbsp;· showing top {{ count($agNames) }}, {{ $agMore }} more not shown
                    @endif
                </p>
                <div id="chart-process-types" style="height:{{ min(60 + count($agNames) * 28, 480) }}px;"></div>
            </div>
        </div>
    </div>
    @endif

</div>
@else
{{-- Neither data set has content yet --}}
<div class="text-center py-5 text-muted">
    <i class="fas fa-database mb-3" style="font-size:2.5rem; opacity:.3;"></i>
    <p>No file or process data loaded yet.<br>
       <a href="{{route('projects.upload-files', [$project])}}">Upload files</a> or
       <a href="{{route('projects.experiments.create', [$project])}}">create a study</a>
       to populate this tab.
    </p>
</div>
@endif

{{-- README --}}
<x-display-markdown-file :file="$readme"></x-display-markdown-file>

@push('scripts')
<script>
(function () {
    const plotConfig = { responsive: true, displayModeBar: false };
    const base = (extra) => Object.assign({
        paper_bgcolor: 'transparent',
        plot_bgcolor:  'transparent',
        font:          { family: 'inherit', size: 11 },
        showlegend:    false,
    }, extra);

    // ── File Types horizontal bar ─────────────────────────────────────────
    @if($hasFileTypes)
    Plotly.newPlot('chart-file-types', [{
        type:          'bar',
        orientation:   'h',
        y:             @json($ftTypes),
        x:             @json($ftCounts),
        marker:        { color: '#0d6efd' },
        hovertemplate: '.%{y}: %{x:,} file(s)<extra></extra>',
        text:          @json(array_map(fn($c) => number_format($c), $ftCounts)),
        textposition:  'inside',
        insidetextanchor: 'end',
        textfont:      { color: 'white', size: 10 },
    }], base({
        margin: { t: 5, b: 30, l: 55, r: 60 },
        xaxis:  { tickformat: ',d', tickfont: { size: 9 }, gridcolor: '#dee2e6' },
        yaxis:  { autorange: 'reversed', tickfont: { size: 10 } },
    }), plotConfig);
    @endif

    // ── Process Types horizontal bar ──────────────────────────────────────
    @if($hasProcessTypes)
    Plotly.newPlot('chart-process-types', [{
        type:          'bar',
        orientation:   'h',
        y:             @json($agNames),
        x:             @json($agCounts),
        marker:        { color: '#0dcaf0' },
        hovertemplate: '%{y}: %{x:,}<extra></extra>',
        text:          @json(array_map(fn($c) => number_format($c), $agCounts)),
        textposition:  'inside',
        insidetextanchor: 'end',
        textfont:      { color: '#000', size: 10 },
    }], base({
        margin: { t: 5, b: 30, l: 155, r: 55 },
        xaxis:  { tickformat: ',d', tickfont: { size: 9 }, gridcolor: '#dee2e6' },
        yaxis:  { autorange: 'reversed', tickfont: { size: 10 } },
    }), plotConfig);
    @endif

    // Initialise Bootstrap tooltips on the metadata strip
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
})();
</script>
@endpush
