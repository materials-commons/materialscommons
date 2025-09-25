<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            Processes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($attributesRouteName)}}" href="{{$attributesRoute}}">
            Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($filesRouteName)}}"
           href="{{$filesRoute}}">
            Files
        </a>
    </li>
</ul>
