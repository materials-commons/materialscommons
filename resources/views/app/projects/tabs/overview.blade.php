<x-show-standard-details :item="$project">
    <a class="ml-3 fs-9" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
    </a>
    <a class="ml-3 fs-9" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
    </a>
</x-show-standard-details>

<hr/>
<br/>

@include('partials.overview._overview')