@if(sizeof($items) >= 12)
    <a href="#"
       onclick="mcutil.toggleShow({{sizeof($items)-11}}, '{{$attrName}}', '{{$msg}}')"
       id="hidden-process-text">
        See {{sizeof($items)-11}} more {{$msg}}...
    </a>
@endif