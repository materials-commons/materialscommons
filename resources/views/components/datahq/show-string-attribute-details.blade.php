@props(['details'])
<div>
    <h5>String Attribute</h5>
    <span class="ml-2">{{implode(', ', array_values($details->values->toArray()))}}</span>
</div>