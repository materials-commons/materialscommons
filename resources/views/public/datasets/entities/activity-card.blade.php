<div class="mt-2">
    <h5>
        <a href="{{route('public.datasets.activities.show', [$dataset, $activity])}}">{{$activity->name}}</a>
    </h5>
    @isset($activity->description)
        <form>
            <div class="mb-3">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endisset
    @include('partials.activities._activity-attributes', ['activity' => $activity])
    <h6>Measurements</h6>
    @include('partials.activities._activity-measurements', ['activity' => $activity])
    <h6>Files</h6>
    @include('public.datasets.entities._activity-files', ['activity' => $activity])
</div>