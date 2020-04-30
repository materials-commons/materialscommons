<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{route($showRouteName, [$community])}}">
            Datasets
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($practicesRouteName)}}"
           href="{{route($practicesRouteName, [$community])}}">
            Recommended Practices
        </a>
    </li>
</ul>