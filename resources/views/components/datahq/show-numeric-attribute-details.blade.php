@props(['details'])
<div>
    <h5>Numeric Attribute</h5>
    <ul>
        <li>Min: {{$details->min}}</li>
        <li>Max: {{$details->max}}</li>
        <li>Count: {{$details->values->count()}}</li>
        <li>Unique: {{$details->uniqueCount}}</li>
    </ul>
</div>