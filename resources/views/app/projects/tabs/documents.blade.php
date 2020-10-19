<p>
    Suggested files, formats, units, and other standards and items used in the project.
</p>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show.files')}}"
           href="{{route('projects.documents.show.files', [$project])}}">
            Files
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show.process-steps')}}"
           href="{{route('projects.documents.show.process-steps', [$project])}}">
            Process Steps
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show.attributes')}}"
           href="{{route('projects.documents.show.attributes', [$project])}}">
            Attribute Descriptions
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show.units')}}"
           href="{{route('projects.documents.show.units', [$project])}}">
            Units
        </a>
    </li>
</ul>

<br>

@if (Request::routeIs('projects.documents.show.files'))
    @include('app.projects.tabs.documents-tabs.files')
@elseif (Request::routeIs('projects.documents.show.process-steps'))
    @include('app.projects.tabs.documents-tabs.process-steps')
@elseif (Request::routeIs('projects.documents.show.attributes'))
    @include('app.projects.tabs.documents-tabs.attributes')
@elseif (Request::routeIs('projects.documents.show.units'))
    @include('app.projects.tabs.documents-tabs.units')
@endif
