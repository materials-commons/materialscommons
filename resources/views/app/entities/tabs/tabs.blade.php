<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            Processes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($attributesRouteName)}}" href="{{$attributesRoute}}">
            Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($filesRouteName)}}"
           href="{{$filesRoute}}">
            Files
        </a>
    </li>
</ul>
