<table>
    <thead>
    <tr>
        <th width="{{$longestEntityNameLen+2}}">Sample</th>
        <th></th>
        @foreach($activityAttributes as $name => $val)
            <th width="{{strlen($name)+5+strlen($val->unit)}}">p:{{$name}}@if(!blank($val->unit))
                    ({{$val->unit}})
                @endif</th>
        @endforeach
        @foreach($entityAttributes as $name => $val)
            <th width="{{strlen($name)+5+strlen($val->unit)}}">s:{{$name}}@if(!blank($val->unit))
                    ({{$val->unit}})
                @endif</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($entities as $entityName => $ignore)
        <tr>
            <td>{{$entityName}}</td>
            <td></td>
            @foreach($activityAttributes as $attrName => $ignore)
                <td>{{$getActivityAttributeValueForEntity($entityName, $attrName)}}</td>
            @endforeach
            @foreach($entityAttributes as $attrName => $ignore)
                <td>{{$getEntityAttributeValue($entityName, $attrName)}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>