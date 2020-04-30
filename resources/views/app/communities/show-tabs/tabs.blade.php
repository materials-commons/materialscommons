<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.communities.show')}}"
           href="{{route('public.communities.show', [$community])}}">
            Datasets
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('public.communities.practices.show')}}"
           href="{{route('public.communities.practices.show', [$community])}}">
            Recommended Practices
        </a>
    </li>
</ul>