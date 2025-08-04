<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('communities.edit')}}"
           href="{{route('communities.edit', [$community])}}">
            Datasets
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('communities.files.edit')}}"
           href="{{route('communities.files.edit', [$community])}}">
            Community Files
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('communities.links.edit')}}"
           href="{{route('communities.links.edit', [$community])}}">
            Community Links
        </a>
    </li>
</ul>
