<ul class="nav nav-pills">
    <li class="nav-item" id="project-home-tab">
        <a class="nav-link no-underline {{setActiveNavByName('projects.show')}}"
           href="{{route('projects.show', [$project])}}">
            Home
        </a>
    </li>

    <li class="nav-item" id="project-overview-tab">
        <a class="nav-link no-underline {{setActiveNavByName('projects.overview')}}"
           href="{{route('projects.overview', [$project])}}">
            Details
        </a>
    </li>

    <li class="nav-item" id="project-sample-attributes-tab">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.entities')}}"
           href="{{route('projects.data-dictionary.entities', [$project])}}">
            Sample Attributes ({{$entityAttributesCount}})
        </a>
    </li>

    <li class="nav-item" id="project-process-attributes-tab">
        <a wire:navigate class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.activities')}}"
           href="{{route('projects.data-dictionary.activities', [$project])}}">
            Process Attributes ({{$activityAttributesCount}})
        </a>
    </li>

    <li class="nav-item" id="project-health-reports-tab">
        <a class="nav-link no-underline {{setActiveNavByName('projects.health-reports.index')}}"
           href="{{route('projects.health-reports.index', [$project])}}">
            @if($project->health == 'critical')
                <span class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i>Health Reports</span>
            @elseif($project->health == 'warning')
                <span class="text-warning"><i class="fa fa-exclamation-circle mr-2"></i>Health Reports</span>
            @elseif(is_null($project->health))
                Health Reports
            @else
                <span><i class="fas fa-check-circle mr-2"></i>Health Reports</span>
            @endif
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show')}}"--}}
    {{--           href="{{route('projects.documents.show', [$project])}}">--}}
    {{--            Units--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
