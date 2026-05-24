@props(['healthStatus' => 'healthy'])

<div class="d-flex justify-content-between align-items-center">
    @php
        $statusClasses = [
            'healthy' => 'text-bg-success',
            'warning' => 'text-bg-warning',
            'critical' => 'text-bg-danger'
        ];
        $statusIcons = [
            'healthy' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'critical' => 'fa-times-circle'
        ];
    @endphp

    <h3 class="mb-0">
        <i class="fas fa-heartbeat"></i>
        Project Health Report
        <span class="ms-4 badge {{ $statusClasses[$healthStatus] }} badge-lg">
            <i class="fas {{ $statusIcons[$healthStatus] }}"></i>
            {{ ucfirst($healthStatus) }}
        </span>
    </h3>
    <div class="health-status">
    </div>
</div>
