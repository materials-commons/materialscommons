<ul class="nav nav-pills">
    <li class="nav-item" id="dashboard-projects-tab">
        <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.show')}}"
           href="{{route('dashboard.projects.show')}}">
            Projects ({{$projectsCount}})
        </a>
    </li>
    <li class="nav-item" id="dashboard-published-datasets-tab">
        <a class="nav-link no-underline {{setActiveNavByName('dashboard.published-datasets.show')}}"
           href="{{route('dashboard.published-datasets.show')}}">
            Published Datasets ({{$publishedDatasetsCount}})
        </a>
    </li>
    <li class="nav-item" id="dashboard-archived-projects-tab">
        <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.archived.index')}}"
           href="{{route('dashboard.projects.archived.index')}}">
            Archived Projects ({{$archivedCount}})
        </a>
    </li>
    <li class="nav-item" id="dashboard-projects-trash-tab">
        <a class="nav-link no-underline {{setActiveNavByName('dashboard.projects.trash.index')}}"
           href="{{route('dashboard.projects.trash.index')}}">
            Projects Scheduled For Deletion ({{$deletedCount}})
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('dashboard.data-dictionary.show')}}"--}}
    {{--           href="{{route('dashboard.data-dictionary.show')}}">--}}
    {{--            Data Dictionary--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
