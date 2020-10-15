<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('dashboard.projects.show')}}"
           href="{{route('dashboard.projects.show')}}">
            Projects
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('dashboard.published-datasets.show')}}"
           href="{{route('dashboard.published-datasets.show')}}">
            Published Datasets
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('dashboard.data-dictionary.show')}}"--}}
    {{--           href="{{route('dashboard.data-dictionary.show')}}">--}}
    {{--            Data Dictionary--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
