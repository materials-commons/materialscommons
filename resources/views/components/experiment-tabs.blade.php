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
        <a class="nav-link {{setActiveNavByName('projects.experiments.entities')}}" href="{{route('projects.experiments.entities.index', [$project, $experiment])}}">
            Samples
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.actions')}}" href="{{route('projects.experiments.actions.index', [$project, $experiment])}}">
            Processes
        </a>
    </li>
</ul>

<br>
