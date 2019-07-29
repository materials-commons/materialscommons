<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.show')}}" href="{{route('projects.experiments.show', [$project, $experiment])}}">
            Details
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.workflow')}}" href="{{route('projects.experiments.workflow.index', [$project, $experiment])}}">
            Workflow
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.samples')}}" href="{{route('projects.experiments.samples.index', [$project, $experiment])}}">
            Samples
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.processes')}}" href="{{route('projects.experiments.processes.index', [$project, $experiment])}}">
            Processes
        </a>
    </li>
</ul>

<br>
