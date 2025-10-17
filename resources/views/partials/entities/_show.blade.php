@component('components.card')
    @slot('header')
        Sample: {{$entity->name}}
        {{--            <a class="float-end action-link" href="#">--}}
        {{--                <i class="fas fa-edit mr-2"></i>Edit--}}
        {{--            </a>--}}

        {{--            <a class="float-end action-link mr-4" href="#">--}}
        {{--                <i class="fas fa-trash-alt mr-2"></i>Delete--}}
        {{--            </a>--}}
    @endslot

    @slot('body')
        <x-show-standard-details :item="$entity"/>

        <br>
        @include('partials.entities.tabs._tabs', [
            'showRouteName' => $showRouteName,
            'showRoute' => $showRoute,
            'attributesRouteName' => $attributesRouteName,
            'attributesRoute' => $attributesRoute,
            'filesRouteName' => $filesRouteName,
            'filesRoute' => $filesRoute,
        ])
        <br>

        @if(Request::routeIs($showRouteName))
            @include('partials.entities.tabs._activities')
        @elseif(Request::routeIs($attributesRouteName))
            @include('partials.entities.tabs._attributes')
        @elseif(Request::routeIs($filesRouteName))
            @include('partials.entities.tabs._files')
        @endif
    @endslot
@endcomponent