<div>
    <x-table.table-search/>
    <table id="activities-dd" class="table table-hover mt-2" style="width:100%">
        <thead class="thead-fixed">
        <tr>
            <th>Attribute</th>
            <th>Units</th>
            <th>Min</th>
            <th>Max</th>
            {{--                <th>Median</th>--}}
            {{--                <th>Avg</th>--}}
            {{--                <th>Mode</th>--}}
            <th># Values</th>
        </tr>
        </thead>
        <tbody>
        @foreach($activityAttributes as $name => $attrs)
            <tr>
                <td>
                    <a href="{{$this->activityAttributeRoute($name)}}">{{$name}}</a>
                </td>
                <td>{{$this->units($attrs)}}</td>
                <td>{{$this->min($attrs)}}</td>
                <td>{{$this->max($attrs)}}</td>
                {{--<td>{{$median($attrs)}}</td>--}}
                {{--<td>{{$average($attrs)}}</td>--}}
                {{--<td>{{$mode($attrs)}}</td>--}}
                <td>{{$attrs->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        {{$activityAttributes->links()}}
    </div>
</div>
