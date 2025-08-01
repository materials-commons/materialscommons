@props(['title', 'subtitle' => null, 'body'])
<div class="card mb-4" style="border-radius: 8px">
    <div class="card-body inner-card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{$title}}</h5>
            @if(!is_null($subtitle))
                {{$subtitle}}
            @endif
        </div>
        <hr/>
        {{$body}}
    </div>
</div>
