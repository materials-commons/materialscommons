{{--
  Prototype v2 project tabs.
  To try it: in show.blade.php change
      @include('app.projects.tabs.tabs')
  to
      @include('app.projects.tabs.tabs-v2')

  Changes vs. original:
    • Icons on every tab for faster scanning
    • Health Reports tab shows a colored pill badge instead of bare text
    • Attribute count badges match the dashboard tab style
    • Active badge color inverts so it stays legible on the highlighted tab
--}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <ul class="nav nav-pills flex-wrap gap-1" role="tablist">

        <li class="nav-item" id="project-home-tab">
            <a class="nav-link no-underline {{setActiveNavByName('projects.show')}}"
               href="{{route('projects.show', [$project])}}"
               aria-current="{{ Request::routeIs('projects.show') ? 'page' : 'false' }}">
                <i class="fas fa-home me-1"></i> Home
            </a>
        </li>

        <li class="nav-item" id="project-overview-tab">
            <a class="nav-link no-underline {{setActiveNavByName('projects.overview')}}"
               href="{{route('projects.overview', [$project])}}"
               aria-current="{{ Request::routeIs('projects.overview') ? 'page' : 'false' }}">
                <i class="fas fa-info-circle me-1"></i> Details
            </a>
        </li>

        <li class="nav-item" id="project-sample-attributes-tab">
            <a class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.entities')}}"
               href="{{route('projects.data-dictionary.entities', [$project])}}"
               aria-current="{{ Request::routeIs('projects.data-dictionary.entities') ? 'page' : 'false' }}">
                <i class="fas fa-cubes me-1"></i>
                Sample Attributes
                <span class="badge rounded-pill ms-1
                    {{ Request::routeIs('projects.data-dictionary.entities') ? 'bg-white text-primary' : 'bg-primary text-white' }}">
                    {{$entityAttributesCount}}
                </span>
            </a>
        </li>

        <li class="nav-item" id="project-process-attributes-tab">
            <a wire:navigate
               class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.activities')}}"
               href="{{route('projects.data-dictionary.activities', [$project])}}"
               aria-current="{{ Request::routeIs('projects.data-dictionary.activities') ? 'page' : 'false' }}">
                <i class="fas fa-cogs me-1"></i>
                Process Attributes
                <span class="badge rounded-pill ms-1
                    {{ Request::routeIs('projects.data-dictionary.activities') ? 'bg-white text-primary' : 'bg-primary text-white' }}">
                    {{$activityAttributesCount}}
                </span>
            </a>
        </li>

        <li class="nav-item" id="project-health-reports-tab">
            <a class="nav-link no-underline {{setActiveNavByName('projects.health-reports.index')}}"
               href="{{route('projects.health-reports.index', [$project])}}"
               aria-current="{{ Request::routeIs('projects.health-reports.index') ? 'page' : 'false' }}">
                @if($project->health === 'critical')
                    <i class="fa fa-exclamation-triangle text-danger me-1"></i>
                    Health
                    <span class="badge rounded-pill bg-danger text-white ms-1">!</span>
                @elseif($project->health === 'warning')
                    <i class="fa fa-exclamation-circle text-warning me-1"></i>
                    Health
                    <span class="badge rounded-pill bg-warning text-white ms-1">!</span>
                @else
                    <i class="fas fa-heartbeat me-1"></i> Health
                @endif
            </a>
        </li>

    </ul>
</div>
