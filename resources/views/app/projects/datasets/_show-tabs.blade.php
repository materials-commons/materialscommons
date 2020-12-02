@include('app.projects.datasets.tabs.tabs')

@if(Request::routeIs('projects.datasets.show.overview'))
    @include('app.projects.datasets.tabs.overview')
@elseif(Request::routeIs('projects.datasets.show.files'))
    @include('app.projects.datasets.tabs.files')
@elseif(Request::routeIs('projects.datasets.show.next'))
    @include('app.projects.datasets.tabs.files')
@elseif(Request::routeIs('projects.datasets.show.entities'))
    @include('app.projects.datasets.tabs.entities')
    {{--@elseif(Request::routeIs('projects.datasets.show.activities'))--}}
    {{--    @include('app.projects.datasets.tabs.activities')--}}
@elseif(Request::routeIs('projects.datasets.show.workflows'))
    @include('app.projects.datasets.tabs.workflows')
@elseif(Request::routeIs('projects.datasets.show.experiments'))
    @include('app.projects.datasets.tabs.experiments')
@elseif(Request::routeIs('projects.datasets.show.communities'))
    @include('app.projects.datasets.tabs.communities')
@elseif(Request::routeIs('projects.datasets.show.data-dictionary'))
    @include('app.projects.datasets.tabs.data-dictionary')
@elseif(Request::routeIs('projects.datasets.show.file_includes_excludes'))
    @include('app.projects.datasets.tabs.file-includes-excludes')
@endif