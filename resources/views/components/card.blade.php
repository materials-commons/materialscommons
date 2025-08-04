@props(['cardBodyClasses' => ""])
<div class="card">
    <div class="card-header">
        <h5>{{$header}}</h5>
    </div>
    <div class="card-body {{$cardBodyClasses}}">
        {{$body}}
    </div>
</div>

<br>
