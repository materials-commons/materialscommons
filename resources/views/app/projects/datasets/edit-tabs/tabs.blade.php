<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.edit')}}"
           href="{{route('projects.datasets.edit', [$project, $dataset])}}">
            Files
        </a>
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