<br>
@include('app.projects.datasets.tabs.tabs')
<br>

@if(Request::routeIs('projects.datasets.show'))
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
@endif