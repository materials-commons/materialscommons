<ul class="nav nav-tabs">
    <li class="nav-item">
        @if(Request::routeIs('projects.datasets.edit'))
            <a class="nav-link active" href="{{route('projects.datasets.files.edit', [$project, $dataset])}}">
                Files
            </a>
        @elseif(Request::routeIs('projects.datasets.files.edit'))
            <a class="nav-link active" href="{{route('projects.datasets.show', [$project, $dataset])}}">
                Files
            </a>
        @else
            <a class="nav-link" href="{{route('projects.datasets.show', [$project, $dataset])}}">
                Files
            </a>
        @endif
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.workflows.edit')}}"
           href="{{route('projects.datasets.workflows.edit', [$project, $dataset])}}">
            Workflows
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.samples.edit')}}"
           href="{{route('projects.datasets.samples.edit', [$project, $dataset])}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.actvities.edit')}}"
           href="{{route('projects.datasets.activities.edit', [$project, $dataset])}}">
            Processes
        </a>
    </li>
</ul>