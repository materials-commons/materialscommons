@props(['activity', 'project', 'user', 'experiment' => null, 'header' => null])
<div class="mt-2">
    <h5 class="mt-3 mr-2 font-weight-bold">
        <a class="no-underline"
           href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
        @if(!is_null($header))
            {{$header}}
        @endif
    </h5>
    <hr/>
    @if(!blank($activity->description))
        <form>
            <div class="form-group">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endisset
    <h6><u>Attributes</u></h6>
    @include('partials.activities._activity-attributes', ['activity' => $activity])
    <h6 class="mt-3"><u>Measurements</u></h6>
    @include('partials.activities._activity-measurements', ['activity' => $activity])
    <h6 class="mt-3"><u>Files</u><h6>
    @include('partials.activities._activity-files', ['activity' => $activity])
</div>
