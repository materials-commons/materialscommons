@props(['healthStatus' => 'healthy'])

<div class="card-header d-flex justify-content-between align-items-center">
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

    <h3 class="mb-0 text-white">
        <i class="fas fa-heartbeat"></i>
        Project Health Report
        <span class="ml-4 badge {{ $statusClasses[$healthStatus] }} badge-lg">
            <i class="fas {{ $statusIcons[$healthStatus] }}"></i>
            {{ ucfirst($healthStatus) }}
        </span>
    </h3>
    <div class="health-status">


    </div>
</div>
