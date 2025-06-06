<table id="activities-dd" class="table table-hover" style="width:100%">
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
    @foreach($activityAttributes as $name => $attrs)
        <tr>
            <td>
                <a href="{{$activityAttributeRoute($name)}}">{{$name}}</a>
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
</table>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigating', () => {
            $('#activities-dd').DataTable().destroy();
        }, {once: true});

        $(document).ready(() => {
            $('#activities-dd').DataTable({
                pageLength: 100,
                stateSave: true
            });
        });
    </script>
@endpush
