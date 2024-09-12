<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByTabParam('samples')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'samples'])}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByTabParam('computations')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'computations'])}}">
            Computations
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline  {{setActiveNavByTabParam('processes')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'processes'])}}">
            Processes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByTabParam('sampleattrs')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'sampleattrs'])}}">
            Sample Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByTabParam('computationattrs')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'computationattrs'])}}">
            Computation Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByTabParam('processattrs')}}"
           href="{{route('projects.datahq.index', [$project, 'tab' => 'processattrs'])}}">
            Process Attributes
        </a>
    </li>

</ul>