<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.show')}}"
           href="{{route('projects.show', [$project])}}">
            Overview
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.documents.show')}}"
           href="{{route('projects.documents.show', [$project])}}">
            Documentation
        </a>
    </li>
</ul>