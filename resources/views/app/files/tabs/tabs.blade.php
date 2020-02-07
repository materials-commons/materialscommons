<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            File
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($entitiesRouteName)}}" href="{{$entitiesRoute}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($activitiesRouteName)}}"
           href="{{$activitiesRoute}}">
            Processes
        </a>
    </li>

    @isset($experimentsRoute)
        <li class="nav-item">
            <a class="nav-link {{setActiveNavByName($experimentsRouteName)}}"
               href="{{$experimentsRoute}}">
                Experiments
            </a>
        </li>
    @endisset
</ul>
