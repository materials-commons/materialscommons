<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.show')}}"
           href="{{route('projects.experiments.show', [$project, $experiment])}}">
            Overview
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.entities')}}"
           href="{{route('projects.experiments.entities', [$project, $experiment])}}">
            Samples ({{$experiment->entities_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.data-dictionary.entities')}}"
           href="{{route('projects.experiments.data-dictionary.entities', [$project, $experiment])}}">
            Sample Attributes ({{$entityAttributesCount}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.data-dictionary.activities')}}"
           href="{{route('projects.experiments.data-dictionary.activities', [$project, $experiment])}}">
            Process Attributes ({{$activityAttributesCount}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.workflow')}}"
           href="{{route('projects.experiments.workflow', [$project, $experiment])}}">
            Workflows ({{$experiment->workflows_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.experiments.etl_runs')}}"
           href="{{route('projects.experiments.etl_runs', [$project, $experiment])}}">
            Spreadsheet Load Logs ({{$etlRunsCount}})
        </a>
    </li>
</ul>
