<table>
    <thead>
    <tr>
        <th width="{{$longestSampleNameLen}}">Sample</th>
        @foreach($uniqueAttributeNames as $name => $unit)
            <th width="25">p:{{$name}}@if(!blank($unit))
                    {{$unit}}
                @endif</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($samples as $sampleActivity)
        <tr>
            <td>{{$sampleActivity[0]->name}}</td>
            @foreach($uniqueAttributeNames as $name => $ignore)
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>