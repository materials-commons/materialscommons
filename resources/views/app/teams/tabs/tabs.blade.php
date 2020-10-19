<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('teams.show')}}"
           href="{{route('teams.show', [$team])}}">
            Projects
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('teams.members.show')}}"
           href="{{route('teams.members.show', [$team])}}">
            Members
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('teams.admins.show')}}"
           href="{{route('teams.admins.show', [$team])}}">
            Admins
        </a>
    </li>
</ul>
