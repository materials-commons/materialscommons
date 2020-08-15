@component('components.item-details', ['item' => $project])
    <a class="ml-4 action-link" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
    </a>
    <a class="ml-4 action-link" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
    </a>
@endcomponent

@include('partials.overview._overview')