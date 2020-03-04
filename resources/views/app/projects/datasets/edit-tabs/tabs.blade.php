<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.edit')}}"
           href="{{route('projects.datasets.edit', [$project, $dataset, 'public' => $isPublic])}}">
            Files
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.workflows.edit')}}"
           href="{{route('projects.datasets.workflows.edit', [$project, $dataset, 'public' => $isPublic])}}">
            Workflows
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.samples.edit')}}"
           href="{{route('projects.datasets.samples.edit', [$project, $dataset, 'public' => $isPublic])}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.activities.edit')}}"
           href="{{route('projects.datasets.activities.edit', [$project, $dataset, 'public' => $isPublic])}}">
            Processes
        </a>
    </li>
</ul>