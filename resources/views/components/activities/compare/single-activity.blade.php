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
    <h5>
        <a href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
    </h5>
    @if(!blank($activity->description))
        <form>
            <div class="form-group">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endif

    <h6>Attributes</h6>
    <x-activities.compare.attributes-highlighter :attrs="$activityAttributes"
                                                 :comparer-state="$activityComparerState"
                                                 :side="$side"/>

    <h6>Measurements</h6>
    <x-activities.compare.attributes-highlighter :attrs="$entityStateAttributes"
                                                 :comparer-state="$entityStateComparerState"
                                                 :side="$side"/>

    <h6>Files</h6>
    @include('partials.activities._activity-files', ['activity' => $activity])
</div>