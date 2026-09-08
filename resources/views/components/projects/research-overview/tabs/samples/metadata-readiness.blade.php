@props([
    'project',
    'metrics' => [],
])

@php
    $total = max(1, (int) ($metrics['totalSamples'] ?? 0));
    $metadataChecks = collect($metrics['metadataChecks'] ?? []);
    $metadataPercent = (int) ($metrics['metadataPercent'] ?? 0);

    $metadataColor = match (true) {
        $metadataPercent >= 80 => 'success',
        $metadataPercent >= 60 => 'info',
        $metadataPercent >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clipboard-check me-1"></i>Sample Metadata
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Metadata readiness for measurements, descriptions, tags, and process context.
                </p>
            </div>

            <span class="badge text-bg-{{ $metadataColor }}">
                {{ $metadataPercent }}% ready
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $metadataColor }}"
                 role="progressbar"
                 style="width: {{ $metadataPercent }}%;"
                 aria-valuenow="{{ $metadataPercent }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        @if((int) ($metrics['totalSamples'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                <div class="fw-semibold">No sample metadata yet</div>
                <div style="font-size:.85rem;">Samples will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            @foreach($metadataChecks as $item)
                @php
                    $percent = round(((int) $item['value'] / $total) * 100);
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2 mb-1">
                        <div class="small text-muted">{{ $item['label'] }}</div>
                        <div class="small fw-semibold">
                            {{ number_format((int) $item['value']) }}
                            <span class="text-muted fw-normal">({{ $percent }}%)</span>
                        </div>
                    </div>

                    <div class="progress" style="height:.45rem;">
                        <div class="progress-bar bg-{{ $item['color'] }}"
                             role="progressbar"
                             style="width: {{ $percent }}%;"
                             aria-valuenow="{{ $percent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>

                    <div class="text-muted mt-1" style="font-size:.72rem;">{{ $item['hint'] }}</div>
                </div>
            @endforeach
        @endif

        <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
           class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-list me-1"></i>Review Sample Attributes
        </a>
    </div>
</div>
