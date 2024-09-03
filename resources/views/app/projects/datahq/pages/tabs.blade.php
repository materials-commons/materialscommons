<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline  {{setActiveNavByName('projects.datahq.index')}}"
           href="{{route('projects.datahq.index', [$project])}}">
            Processes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.entities')}}"
           href="{{route('projects.datahq.entities', [$project])}}">
            Sample Attributes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline" href="#">
            Process Attributes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.results')}}"
           href="{{route('projects.datahq.results', [$project])}}">
            Results
        </a>
    </li>
</ul>