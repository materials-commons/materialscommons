<h3 class="text-center">
    @if($activity->category == "computational")
        Activity: {{$activity->name}}
    @else
        Process: {{$activity->name}}
    @endif
</h3>

<br/>
<x-show-standard-details :item="$activity"/>
<br>
@include('partials.activities.tabs._tabs', [
    'showRouteName' => $showRouteName,
    'showRoute' => $showRoute,
    'entitiesRouteName' => $entitiesRouteName,
    'entitiesRoute' => $entitiesRoute,
    'filesRouteName' => $filesRouteName,
    'filesRoute' => $filesRoute
])
<br>

<x-card-container>
    @if(Request::routeIs($showRouteName))
        @include('partials.activities.tabs._attributes')
    @elseif(Request::routeIs($entitiesRouteName))
        @include('partials.activities.tabs._entities')
    @elseif(Request::routeIs($filesRouteName))
        @include('partials.activities.tabs._files')
    @endif
</x-card-container>
