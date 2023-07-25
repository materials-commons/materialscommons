<ul class="list-unstyled ml-4">
    @foreach($activities as $activity)
        <li>
            @if(old('activities'))
                <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                       {{in_array($activity->name, old('activities')) ? 'checked' : ''}}
                       hx-post="{{route('projects.entities.mql.show', $project)}}"
                       hx-include="#mql-selection"
                       hx-target="#mql-query"
                       hx-trigger="click">
            @else
                <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                       hx-post="{{route('projects.entities.mql.show', $project)}}"
                       hx-include="#mql-selection"
                       hx-target="#mql-query"
                       hx-trigger="click">
            @endif
            {{$activity->name}}
        </li>
    @endforeach
</ul>