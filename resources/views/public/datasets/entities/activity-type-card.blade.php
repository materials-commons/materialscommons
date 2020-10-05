<div class="mt-2">
    <h5>
        {{$activityType->name}} ({{$activityType->count}})
    </h5>
    {{--    @isset($activity->description)--}}
    {{--        <form>--}}
    {{--            <div class="form-group">--}}
    {{--                <textarea class="form-control" readonly>{{$activity->description}}</textarea>--}}
    {{--            </div>--}}
    {{--        </form>--}}
    {{--    @endisset--}}
    @if(sizeof($attributes) != 0)
        @include('partials.activities._activity-type-attributes', ['attributes' => $attributes])
        <h6>Measurements</h6>
    @endif
    @if (sizeof($measurements) != 0)
        <h6>Measurements</h6>
        @include('partials.activities._activity-type-measurements', ['measurements' => $measurements])
    @endif
    @if(sizeof($files) != 0)
        <h6>Files</h6>
        @include('public.datasets.entities._activity-type-files', ['files' => $files])
    @endif
</div>