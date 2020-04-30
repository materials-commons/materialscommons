@component('components.card')
    @slot('header')
        Community: {{$community->name}}
        @isset($editCommunityRoute)
            <a class="action-link float-right" href="{{$editCommunityRoute}}">
                <i class="fas fa-edit mr-2"></i>Edit Community
            </a>
        @endisset
    @endslot

    @slot('body')
        <form>
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" id="name" name="name" value="{{$community->name}}" type="text"
                       placeholder="Name..." readonly>
            </div>

            <div class="form-group">
                <label for="name">Summary</label>
                <input class="form-control" id="summary" name="summary" value="{{$community->summary}}" type="text"
                       placeholder="Summary..." readonly>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" type="text"
                          placeholder="Description..." readonly>{{$community->description}}</textarea>
            </div>
        </form>

        <br>
        @include('partials.communities.show-tabs.tabs')
        <br>

        @if(Request::routeIs($showRouteName))
            @include('partials.communities.show-tabs.datasets')
        @elseif (Request::routeIs($practicesRouteName))
            @include('partials.communities.show-tabs.practices')
        @endif

        <br>
        <div class="float-right">
            <a class="btn btn-success" href="{{$doneRoute}}">Done</a>
        </div>
    @endslot
@endcomponent