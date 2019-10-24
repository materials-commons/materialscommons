<ul class="nav nav-tabs">
    <li class="nav-item">
        @if(Request::routeIs('projects.datasets.show'))
            <a class="nav-link active" href="{{route('projects.datasets.show', [$project, $dataset])}}">
                Files
            </a>
        @elseif(Request::routeIs('projects.datasets.show.next'))
            <a class="nav-link active" href="{{route('projects.datasets.show', [$project, $dataset])}}">
                Files
            </a>
        @else
            <a class="nav-link" href="{{route('projects.datasets.show', [$project, $dataset])}}">
                Files
            </a>
        @endif
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.datasets.show.entities')}}"
           href="{{route('projects.datasets.show.entities', [$project, $dataset])}}">
            Samples
        </a>
    </li>
</ul>