<ul class="list-unstyled ml-4">
    @foreach($activities as $activity)
        <li>
            @if(old('activities'))
                <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                        {{in_array($activity->name, old('activities')) ? 'checked' : ''}}>
            @else
                <input type="checkbox" name="activities[]" value="{{$activity->name}}">
            @endif
            {{$activity->name}}
        </li>
    @endforeach
</ul>