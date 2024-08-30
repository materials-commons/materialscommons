<table id="entities-dd" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Attribute</th>
        <th>Units</th>
        <th>Min</th>
        <th>Max</th>
        {{--                <th>Median</th>--}}
        {{--                <th>Avg</th>--}}
        {{--                <th>Mode</th>--}}
        <th># Values</th>
        <th>Query On</th>
        <th>Select</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entityAttributes as $name => $attrs)
        <tr>
            <td>
                <a href="{{$entityAttributeRoute($name)}}">{{$name}}</a>
            </td>
            <td>{{$units($attrs)}}</td>
            <td>{{$min($attrs)}}</td>
            <td>{{$max($attrs)}}</td>
            {{--                    <td>{{$median($attrs)}}</td>--}}
            {{--                    <td>{{$average($attrs)}}</td>--}}
            {{--                    <td>{{$mode($attrs)}}</td>--}}
            <td>{{$attrs->count()}}</td>
            @if($loop->index == 1)
                <td>
                    <input type="checkbox" checked="true" name="x"/>
                    <div class="row ml-1">
                        <select id="select-{{$loop->index}}"
                                class="selectpicker col-6">
                            <option>Select</option>
                            <option>=</option>
                            <option>></option>
                            <option>>=</option>
                            <option><</option>
                            <option><=</option>
                            <option><></option>
                        </select>
                        <input type="text" placeholder="Value..." class="col-4">
                    </div>
                </td>
            @else
                <td><input type="checkbox"></td>
            @endif

            <td><input type="checkbox"></td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities-dd').DataTable({
                pageLength: 100,
                stateSave: true
            });
        });
    </script>
@endpush
