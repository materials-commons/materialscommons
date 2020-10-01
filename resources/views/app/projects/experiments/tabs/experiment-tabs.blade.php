<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.show')}}"
           href="{{route('projects.experiments.show', [$project, $experiment])}}">
            Overview
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.entities')}}"
           href="{{route('projects.experiments.entities', [$project, $experiment])}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.data-dictionary.entities')}}"
           href="{{route('projects.experiments.data-dictionary.entities', [$project, $experiment])}}">
            Sample Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.data-dictionary.activities')}}"
           href="{{route('projects.experiments.data-dictionary.activities', [$project, $experiment])}}">
            Process Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.workflow')}}"
           href="{{route('projects.experiments.workflow', [$project, $experiment])}}">
            Workflows
        </a>
    </li>
</ul>
