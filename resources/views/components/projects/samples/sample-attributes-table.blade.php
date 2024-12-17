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
        </tr>
    @endforeach
    </tbody>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#entities-dd').DataTable({
                    pageLength: 100,
                    scrollX: true,
                    stateSave: true,
                    fixedHeader: {
                        header: true,
                        headerOffset: 46,
                    },
                });
            });
        </script>
    @endpush

    @script
    <script>
        $wire.on('reload-component', () => {
            $('#entities-dd').DataTable().destroy();
            setTimeout(() => {
                $('#entities-dd').DataTable({
                    pageLength: 100,
                    scrollX: true,
                    stateSave: true,
                    fixedHeader: {
                        header: true,
                        headerOffset: 46,
                    },
                });
            });
        });
    </script>
    @endscript
</table>


