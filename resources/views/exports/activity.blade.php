<table>
    <thead>
    <tr>
        <th width="{{$longestSampleNameLen+2}}">Sample</th>
        @foreach($uniqueActivityAttributeNames as $name => $unit)
            <th width="{{strlen($name)+strlen($unit)+4}}">p:{{$name}}@if(!blank($unit))
                    {{$unit}}
                @endif</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($samples as $sampleActivity)
        <tr>
            <td>{{$sampleActivity[0]->name}}</td>
            @foreach($uniqueActivityAttributeNames as $name => $ignore)
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>