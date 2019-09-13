<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($entitiesRouteName)}}" href="{{$entitiesRoute}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($filesRouteName)}}"
           href="{{$filesRoute}}">
            Files
        </a>
    </li>
</ul>
