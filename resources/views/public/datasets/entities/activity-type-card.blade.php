<div class="mt-2">
    <h5 class="mt-3 me-2 font-weight-bold">
        {{$activityType->name}} ({{$activityType->count}})
    </h5>
    <hr/>
    {{--    @isset($activity->description)--}}
    {{--        <form>--}}
    {{--            <div class="form-group">--}}
    {{--                <textarea class="form-control" readonly>{{$activity->description}}</textarea>--}}
    {{--            </div>--}}
    {{--        </form>--}}
    {{--    @endisset--}}
    @if(sizeof($attributes) != 0)
        <h6><u>Settings</u></h6>
        @include('partials.activities._activity-type-attributes', ['attributes' => $attributes])
    @endif
    @if (sizeof($measurements) != 0)
        <h6 class="mt-3"><u>Measurements</u></h6>
        @include('partials.activities._activity-type-measurements', ['measurements' => $measurements])
    @endif
    @if(sizeof($files) != 0)
        <h6 class="mt-3"><u>Files</u></h6>
        @include('public.datasets.entities._activity-type-files', ['files' => $files])
    @endif
</div>
