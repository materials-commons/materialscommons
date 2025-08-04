{{--<div>--}}
{{--        <x-show-standard-details :item="$project">--}}
{{--            <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">--}}
{{--                {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())--}}
{{--            </a>--}}
{{--            <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">--}}
{{--                {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())--}}
{{--            </a>--}}
{{--            <span class="ml-3 fs-10 grey-5">Size: {{formatBytes($project->size)}}</span>--}}
{{--            <span class="ml-3 fs-10 grey-5">Slug: {{$project->slug}}</span>--}}
{{--            <span id="mark-project">--}}
{{--                @if(auth()->user()->isActiveProject($project))--}}
{{--                    <a hx-get="{{route('projects.unmark-as-active', [$project, 'target' => 'mark-project'])}}"--}}
{{--                       hx-target="#mark-project"--}}
{{--                       class="btn btn-info float-right cursor-pointer">Remove From Active Projects</a>--}}
{{--                @else--}}
{{--                    <a hx-get="{{route('projects.mark-as-active', [$project, 'target' => 'mark-project'])}}"--}}
{{--                       hx-target="#mark-project"--}}
{{--                       class="btn btn-success float-right cursor-pointer">Mark As Active Project</a>--}}
{{--                @endif--}}
{{--            </span>--}}
{{--        </x-show-standard-details>--}}
{{--    <br/>--}}
{{--</div>--}}


<div class="row">
    <div class="col">
        <div class="card-deck">
            @include('app.projects.tabs.home._files-card')
            @include('app.projects.tabs.home._studies-card')
            @include('app.projects.tabs.home._datasets-card')
        </div>
    </div>
</div>

<x-display-markdown-file :file="$readme"></x-display-markdown-file>
