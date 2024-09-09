<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.sampleshq.index')}}"
           href="{{route('projects.datahq.sampleshq.index', [$project])}}">
            Samples
        </a>
    </li>
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
</ul>