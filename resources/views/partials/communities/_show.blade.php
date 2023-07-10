@component('components.card')
    @slot('header')
        Community: {{$community->name}}
        @isset($editCommunityRoute)
            <a class="action-link float-right" href="{{$editCommunityRoute}}">
                <i class="fas fa-edit mr-2"></i>Edit Community
            </a>
        @endisset
        @guest
        @else
            <a class="action-link float-right mr-4" href="#">
                <i class="fas fa-plus mr-2"></i>Add Dataset
            </a>
        @endguest

    @endslot

    @slot('body')
        @include('partials.communities.show-tabs.tabs')
        <br>
        @if(Request::routeIs($showRouteName))
            @include('partials.communities.show-tabs.overview')
        @elseif (Request::routeIs($datasetsRouteName))
            @include('partials.communities.show-tabs.datasets')
        @elseif (Request::routeIs($filesRouteName))
            @include('partials.communities.show-tabs.files')
        @elseif (Request::routeIs($linksRouteName))
            @include('partials.communities.show-tabs.links')
        @endif

        <br>
        <div class="float-right">
            <a class="btn btn-success" href="{{$doneRoute}}">Done</a>
        </div>
    @endslot
@endcomponent