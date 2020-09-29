<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{route($showRouteName, [$community])}}">
            Overview
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($datasetsRouteName)}}"
           href="{{route($datasetsRouteName, [$community])}}">
            Datasets
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($filesRouteName)}}" href="{{route($filesRouteName, [$community])}}">
            Files
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($linksRouteName)}}" href="{{route($linksRouteName, [$community])}}">
            Links
        </a>
    </li>
</ul>
