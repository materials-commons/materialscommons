@php
    $detailsActive   = Request::routeIs('projects.datasets.edit');
    $filesActive     = Request::routeIs('projects.datasets.files.edit');
    $samplesActive   = Request::routeIs('projects.datasets.samples.edit');
    $workflowsActive = Request::routeIs('projects.datasets.workflows.edit');
@endphp

<ul class="nav nav-tabs mb-0" role="tablist">
    {{-- Details --}}
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2 {{ $detailsActive ? 'active' : '' }}"
           href="{{ $defaultRoute }}">
            @if(blank($dataset->description))
                <i class="fas fa-exclamation-circle text-danger" style="font-size:.78rem;"></i>
            @else
                <i class="fas fa-check-circle text-success" style="font-size:.78rem;"></i>
            @endif
            Details
        </a>
    </li>

    {{-- Files --}}
    <li class="nav-item" id="files-step">
        <a class="nav-link d-flex align-items-center gap-2 {{ $filesActive ? 'active' : '' }}"
           href="{{ $filesRoute }}">
            @if($dataset->hasSelectedFiles())
                <i class="fas fa-check-circle text-success" id="files-circle" style="font-size:.78rem;"></i>
            @else
                <i class="fas fa-exclamation-circle text-danger" id="files-circle" style="font-size:.78rem;"></i>
            @endif
            Files
        </a>
    </li>

    {{-- Samples (optional) --}}
    <li class="nav-item" id="samples-step">
        <a class="nav-link d-flex align-items-center gap-2 {{ $samplesActive ? 'active' : '' }}"
           href="{{ $samplesRoute }}">
            @if($entities->count() > 0)
                <i class="fas fa-check-circle text-success" id="samples-circle" style="font-size:.78rem;"></i>
            @else
                <i class="fas fa-circle text-muted" id="samples-circle" style="font-size:.78rem;"></i>
            @endif
            Samples
            <span class="badge text-bg-light border" style="font-size:.6rem; font-weight:400; padding:.15em .35em;">optional</span>
        </a>
    </li>

    {{-- Workflows (optional) --}}
    <li class="nav-item" id="workflows-step">
        <a class="nav-link d-flex align-items-center gap-2 {{ $workflowsActive ? 'active' : '' }}"
           href="{{ $workflowsRoute }}">
            @if($dataset->workflows->count() > 0)
                <i class="fas fa-check-circle text-success" id="workflows-circle" style="font-size:.78rem;"></i>
            @else
                <i class="fas fa-circle text-muted" id="workflows-circle" style="font-size:.78rem;"></i>
            @endif
            Workflows
            <span class="badge text-bg-light border" style="font-size:.6rem; font-weight:400; padding:.15em .35em;">optional</span>
        </a>
    </li>

    {{-- Published indicator — non-clickable, pushed to far right --}}
    <li class="nav-item ms-auto">
        <span class="nav-link d-flex align-items-center gap-2 pe-none text-muted" style="cursor:default;">
            @if(!is_null($dataset->published_at))
                <i class="fas fa-check-circle text-success" style="font-size:.78rem;"></i>
                Published
            @else
                <i class="fas fa-lock-open text-muted" style="font-size:.78rem;"></i>
                Unpublished
            @endif
        </span>
    </li>
</ul>
