<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.overview')}}"
           href="{{route('public.datasets.overview.show', ['dataset' => $dataset])}}">
            Overview
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.folders')}}"
           href="{{route('public.datasets.folders.show', [$dataset, '-1'])}}"> Files ({{$dataset->files_count}})</a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.entities')}}"
           href="{{route('public.datasets.entities.index', ['dataset' => $dataset])}}">
            Samples ({{$dataset->entities_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.workflows')}}"
           href="{{route('public.datasets.workflows.index', ['dataset' => $dataset])}}">
            Workflows ({{$dataset->workflows_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.communities')}}"
           href="{{route('public.datasets.communities.index', [$dataset])}}">
            Communities ({{$dataset->communities_count}})
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.comments')}}"
           href="{{route('public.datasets.comments.index', ['dataset' => $dataset])}}">
            Comments ({{$dataset->comments_count}})
        </a>
    </li>
</ul>
