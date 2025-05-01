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
                Studies ({{$file->experiments_count}})
            </a>
        </li>
    @endisset

    @isset($versionsRoute)
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByName($versionsRouteName)}}"
               href="{{$versionsRoute}}">
                Versions ({{$previousVersions->count()}})
            </a>
        </li>
    @endisset
</ul>
