@props(['header', 'body'])
<div {{$attributes->merge(['class' => 'card'])}}>
    @if(isset($header))
        <div class="card-header">
            <h5>{{$header}}</h5>
        </div>
    @endif
    <div class="card-body">
        {{$body}}
    </div>
</div>

<br>