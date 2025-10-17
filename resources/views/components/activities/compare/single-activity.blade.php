@props([
    'activity',
    'project',
    'side',
    'activityAttributes',
    'activityComparerState',
    'entityStateAttributes',
    'entityStateComparerState'
])
<div class="mt-2">
    <h5 class="mt-3 me-2 font-weight-bold">
        <a class="no-underline"
           href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
    </h5>
    <hr/>
    @if(!blank($activity->description))
        <form>
            <div class="form-group">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endif

    <h6><u>Attributes</u></h6>
    <x-activities.compare.attributes-highlighter :attrs="$activityAttributes"
                                                 :comparer-state="$activityComparerState"
                                                 :side="$side"/>

    <h6 class="mt-3"><u>Measurements</u></h6>
    <x-activities.compare.attributes-highlighter :attrs="$entityStateAttributes"
                                                 :comparer-state="$entityStateComparerState"
                                                 :side="$side"/>

    {{--    <h6 class="mt-3"><u>Files</u></h6>--}}
    {{--    @include('partials.activities._activity-files', ['activity' => $activity])--}}
</div>
