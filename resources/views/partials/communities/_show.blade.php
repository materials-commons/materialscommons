@component('components.card')
    @slot('header')
        Community: {{$community->name}}
        @isset($editCommunityRoute)
            <a class="action-link float-end" href="{{$editCommunityRoute}}">
                <i class="fas fa-edit mr-2"></i>Edit Community
            </a>
        @endisset
        @guest
        @else
            @if(isset($userDatasets))
                @if($userDatasets->isNotEmpty())
                    <div class="dropdown float-end">
                        <a class="action-link mr-4 dropdown-toggle" data-toggle="dropdown"
                           data-offset="20" data-boundary="viewport"
                           href="#">
                            <i class="fas fa-plus mr-2"></i>Add Dataset
                        </a>
                        <div class="dropdown-menu" data-offset="20" data-boundary="viewport">
                            @foreach($userDatasets as $userDataset)
                                <a class="dropdown-item td-none"
                                   href="{{route('public.communities.dataset.request-added', [$community, $userDataset])}}">
                                    {{$userDataset->name}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        @endguest

    @endslot

    @slot('body')
        @include('partials.communities.show-tabs.tabs')
        <br>
        <x-card-container>
            @if(Request::routeIs($showRouteName))
                @include('partials.communities.show-tabs.overview')
            @elseif (Request::routeIs($datasetsRouteName))
                @include('partials.communities.show-tabs.datasets')
            @elseif (Request::routeIs($filesRouteName))
                @include('partials.communities.show-tabs.files')
            @elseif (Request::routeIs($linksRouteName))
                @include('partials.communities.show-tabs.links')
            @elseif(Request::routeIs('communities.waiting-approval.index'))
                @include('partials.communities.show-tabs.datasets-waiting-approval')
            @endif

            <br>
            <div class="float-end">
                <a class="btn btn-success" href="{{$doneRoute}}">Done</a>
            </div>
        </x-card-container>
    @endslot
@endcomponent
