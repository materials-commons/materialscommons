@props([
    'samples' => null,
    'computations' => null,
    'processes' => null,
    'workflows' => null,
    'attributeCount' => null,
    'totalSize' => null,
    'samplesHint' => 'experimental',
    'computationsHint' => 'computational',
    'processesHint' => 'activities',
    'workflowsHint' => 'defined',
    'attributeCountHint' => '',
    'totalSizeHint' => 'file storage',
    'totalSizeFormatted' => false,
])

@php
    $formatValue = function ($value, bool $formatted = false): string {
        if ($formatted) {
            return (string) $value;
        }

        if (is_numeric($value)) {
            return number_format((float) $value);
        }

        return (string) $value;
    };

    $kpis = collect([
        !is_null($samples) ? [
            'label' => 'Samples',
            'value' => $formatValue($samples),
            'icon' => 'fas fa-cubes',
            'color' => 'primary',
            'hint' => $samplesHint,
        ] : null,

        !is_null($computations) ? [
            'label' => 'Computations',
            'value' => $formatValue($computations),
            'icon' => 'fas fa-microchip',
            'color' => 'info',
            'hint' => $computationsHint,
        ] : null,

        !is_null($processes) ? [
            'label' => 'Processes',
            'value' => $formatValue($processes),
            'icon' => 'fas fa-cogs',
            'color' => 'success',
            'hint' => $processesHint,
        ] : null,

        !is_null($workflows) ? [
            'label' => 'Workflows',
            'value' => $formatValue($workflows),
            'icon' => 'fas fa-project-diagram',
            'color' => 'secondary',
            'hint' => $workflowsHint,
        ] : null,

        !is_null($attributeCount) ? [
            'label' => 'Attributes',
            'value' => $formatValue($attributeCount),
            'icon' => 'fas fa-list',
            'color' => 'warning',
            'hint' => $attributeCountHint,
        ] : null,

        !is_null($totalSize) ? [
            'label' => 'Total Size',
            'value' => $formatValue($totalSize, $totalSizeFormatted),
            'icon' => 'fas fa-database',
            'color' => 'muted',
            'hint' => $totalSizeHint,
        ] : null,
    ])->filter()->values();

    $columnClass = match (true) {
        $kpis->count() >= 6 => 'col-6 col-md-4 col-xl-2',
        $kpis->count() === 5 => 'col-6 col-md-4 col-xl',
        $kpis->count() === 4 => 'col-6 col-md-3',
        $kpis->count() === 3 => 'col-6 col-md-4',
        $kpis->count() === 2 => 'col-6',
        default => 'col-12',
    };
@endphp

@if($kpis->isNotEmpty())
    <div class="row g-3 mb-4">
        @foreach($kpis as $kpi)
            @php
                $color = $kpi['color'] ?? 'secondary';
                $iconBackground = $color === 'muted' ? 'bg-light' : "bg-{$color}-subtle";
                $textColor = $color === 'muted' ? 'text-muted' : "text-{$color}";
            @endphp

            <div class="{{ $columnClass }}">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-2 background-white">
{{--                        <div class="kpi-icon {{ $textColor }} {{ $iconBackground }}">--}}
{{--                            <i class="{{ $kpi['icon'] }}"></i>--}}
{{--                        </div>--}}

                        <div class="text-muted small mb-1 text-primary">
                            {{ $kpi['label'] }}
                        </div>

                        <div class="fw-bold fs-5 {{$textColor}}">
                            {{ $kpi['value'] }}
                        </div>

                        @if(filled($kpi['hint'] ?? null))
                            <div class="text-muted" style="font-size:.65rem;">
                                {{ $kpi['hint'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
