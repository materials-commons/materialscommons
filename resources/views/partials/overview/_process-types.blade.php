@if(isset($activitiesGroup) && sizeof($activitiesGroup) != 0)
    <div class="mb-3">
        <label for="process-types">Process Types ({{sizeof($activitiesGroup)}})</label>
        <ul class="list-inline">
            @foreach($activitiesGroup as $ag)
                <li class="list-inline-item mt-1">
                    {{--                <span class="badge badge-light fs-11">{{$ag->name}} ({{$ag->count}})</span>--}}
                    <span class="grey-5 fs-10">{{$ag->name}} ({{$ag->count}})</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
