<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($entitiesRouteName)}}" href="{{$entitiesRoute}}">
            @if($activity->category == "computational")
                Computations
            @else
                Samples
            @endif
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($filesRouteName)}}"
           href="{{$filesRoute}}">
            Files
        </a>
    </li>
</ul>
