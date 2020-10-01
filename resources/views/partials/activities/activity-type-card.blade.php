<div class="mt-2">
    <h5 style="text-decoration: underline">
        {{$activityType->name}} ({{$activityType->count}})
    </h5>
    @isset($activity->description)
        <form>
            <div class="form-group">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endisset
    @if(sizeof($attributes) != 0)
        <h6>Process Settings</h6>
        @include('partials.activities._activity-type-attributes', ['attributes' => $attributes])
    @endif
    @if (sizeof($measurements) != 0)
        <h6>Measurements</h6>
        @include('partials.activities._activity-type-measurements', ['measurements' => $measurements])
    @endif
    @if(sizeof($files) != 0)
        <h6>Files</h6>
        @include('partials.activities._activity-type-files', ['files' => $files])
    @endif
</div>