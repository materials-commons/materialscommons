@props(['details'])
<div>
    <h5>String Attribute</h5>
    <ul>
        <li>Count: {{$details->values->count()}}</li>
        <li>Unique: {{$details->uniqueCount}}</li>
    </ul>
    <h5>Values (Showing {{$details->valuesToShow->count()}} of {{$details->values->count()}} values)</h5>
    <ul class="list-unstyled">
        @foreach($details->valuesToShow as $val)
            <li>{{$val}}</li>
        @endforeach
    </ul>
</div>