<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.sampleshq.index')}}"
           href="{{route('projects.datahq.sampleshq.index', [$project])}}">
            Samples
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline  {{setActiveNavByName('projects.datahq.sampleshq.activities.filters')}}"
           href="{{route('projects.datahq.sampleshq.activities.index', [$project])}}">
            Processes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.sampleshq.entities.filters')}}"
           href="{{route('projects.datahq.sampleshq.entities.data-dictionary', [$project])}}">
            Sample Attributes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datahq.sampleshq.activities.filters')}}"
           href="{{route('projects.datahq.sampleshq.activities.data-dictionary', [$project])}}">
            Process Attributes
        </a>
    </li>
</ul>