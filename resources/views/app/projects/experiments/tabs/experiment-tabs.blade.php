<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.show')}}" href="{{route('projects.experiments.show', [$project, $experiment])}}">
            Workflow
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.entities-tab')}}"
           href="{{route('projects.experiments.entities-tab', [$project, $experiment])}}">
            Samples
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.activities-tab')}}"
           href="{{route('projects.experiments.activities-tab', [$project, $experiment])}}">
            Processes
        </a>
    </li>
</ul>
