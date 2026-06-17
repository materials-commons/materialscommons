@props([
    'project',
    'metrics' => [],
])

@php
    $items = collect($metrics['teamCoverageItems'] ?? []);
    $total = max(1, (int) ($metrics['totalPeopleSignals'] ?? 1));
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-user-check me-1"></i>Team Coverage
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Project owner, admins, members, and dataset author signals.
                </p>
            </div>

            <span class="badge text-bg-primary">
                {{ number_format((int) ($metrics['teamUserCount'] ?? 0)) }} team
            </span>
        </div>

        @foreach($items as $item)
            <x-projects.research-overview.analytics.metric-row
                :label="$item['label']"
                :value="$item['value']"
                :total="$total"
                :color="$item['color']"
                :hint="$item['hint']"
            />
        @endforeach
    </div>
</div>
