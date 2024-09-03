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
        <th>Query</th>
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

            <td>
                <a href="#query-dialog" data-toggle="modal" class="action-link">
                    <i class="fas fa-fw fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('app.projects.datahq.pages._query-dialog')

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
