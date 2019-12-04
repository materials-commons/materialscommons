<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.datasets.show')}}"
           href="{{route('public.datasets.show', ['dataset' => $dataset])}}">
            Workflow
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.datasets.entities')}}"
           href="{{route('public.datasets.entities.index', ['dataset' => $dataset])}}">
            Samples
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.datasets.activities')}}"
           href="{{route('public.datasets.activities.index', ['dataset' => $dataset])}}">
            Processes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.datasets.files')}}"
           href="{{route('public.datasets.files.index', ['dataset' => $dataset])}}">
            Files
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.datasets.comments')}}"
           href="{{route('public.datasets.comments.index', ['dataset' => $dataset])}}">
            Comments
        </a>
    </li>
</ul>