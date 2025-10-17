@component('components.card')
    @slot('header')
        @if($activity->category == "computational")
            Activity: {{$activity->name}}
        @else
            Process: {{$activity->name}}
        @endif
        {{--        <a class="float-end action-link" href="#">--}}
        {{--            <i class="fas fa-edit mr-2"></i>Edit--}}
        {{--        </a>--}}

        {{--        <a class="float-end action-link mr-4" href="#">--}}
        {{--            <i class="fas fa-trash-alt mr-2"></i>Delete--}}
        {{--        </a>--}}
    @endslot

    @slot('body')
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
    @endslot
@endcomponent
