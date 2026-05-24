<div class="row mt-2 ms-1">
    <a href="#"
       hx-get="{{route('projects.activities.attributes.show-details-by-name', [$project, $attrName])}}"
       hx-target="#activity-attribute-overview">{{$attrName}}</a>
</div>
<div class="row ms-1">
    <select class="col-6" name="xx" value="">
        <option>Select</option>
        <option>=</option>
        <option>></option>
        <option>>=</option>
        <option><</option>
        <option><=</option>
        <option><></option>
    </select>
    <input type="text" placeholder="Value..." class="col-4"
           name="yy">
</div>