<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('communities.edit')}}"
           href="{{route('communities.edit', [$community])}}">
            Datasets
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('communities.files.edit')}}"
           href="{{route('communities.files.edit', [$community])}}">
            Recommended Practices Files
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('communities.links.edit')}}"
           href="{{route('communities.links.edit', [$community])}}">
            Recommended Practices Links
        </a>
    </li>
</ul>