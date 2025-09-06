@props(['healthStatus' => 'healthy'])

<div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="mb-0">
        <i class="fas fa-heartbeat"></i>
        Project Health Report
    </h3>
    <div class="health-status">
        @php
            $statusClasses = [
                'healthy' => 'badge-success',
                'warning' => 'badge-warning',
                'critical' => 'badge-danger'
            ];
            $statusIcons = [
                'healthy' => 'fa-check-circle',
                'warning' => 'fa-exclamation-triangle',
                'critical' => 'fa-times-circle'
            ];
        @endphp
        <span class="badge {{ $statusClasses[$healthStatus] }} badge-lg">
            <i class="fas {{ $statusIcons[$healthStatus] }}"></i>
            {{ ucfirst($healthStatus) }}
        </span>
    </div>
</div>
