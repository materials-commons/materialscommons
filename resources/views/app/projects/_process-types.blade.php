<div class="mt-2">
    <h5>Process Types ({{sizeof($activitiesGroup)}}):</h5>
    <ul id="process-types">
        @foreach($activitiesGroup as $ag)
            @if($loop->iteration < 12)
                <li>{{$ag->name}} ({{$ag->count}})</li>
            @else
                <li class="hidden-process" hidden>{{$ag->name}} ({{$ag->count}})</li>
            @endif
        @endforeach
        @include('common.show-more-control', [
            'items'    => $activitiesGroup,
            'attrName' => 'hidden-process',
            'msg'      => 'processes...'
        ])
    </ul>
</div>