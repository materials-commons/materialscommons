<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            File
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($entitiesRouteName)}}" href="{{$entitiesRoute}}">
            Samples ({{$file->entities_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($activitiesRouteName)}}"
           href="{{$activitiesRoute}}">
            Processes ({{$file->activities_count}})
        </a>
    </li>

    @isset($experimentsRoute)
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByName($experimentsRouteName)}}"
               href="{{$experimentsRoute}}">
                Experiments ({{$file->experiments_count}})
            </a>
        </li>
    @endisset
</ul>
