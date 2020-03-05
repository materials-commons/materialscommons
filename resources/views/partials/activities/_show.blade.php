@component('components.card')
    @slot('header')
        Process: {{$activity->name}}
        {{--        <a class="float-right action-link" href="#">--}}
        {{--            <i class="fas fa-edit mr-2"></i>Edit--}}
        {{--        </a>--}}

        {{--        <a class="float-right action-link mr-4" href="#">--}}
        {{--            <i class="fas fa-trash-alt mr-2"></i>Delete--}}
        {{--        </a>--}}
    @endslot

    @slot('body')
        @component('components.item-details', ['item' => $activity])
        @endcomponent
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

        @if(Request::routeIs($showRouteName))
            @include('partials.activities.tabs._attributes')
        @elseif(Request::routeIs($entitiesRouteName))
            @include('partials.activities.tabs._entities')
        @elseif(Request::routeIs($filesRouteName))
            @include('partials.activities.tabs._files')
        @endif
    @endslot
@endcomponent