<h3 class="text-center">Data Dictionary For All Projects</h3>
<br/>

<h5>Processes Data Dictionary</h5>
<br>
<table id="activities-dd" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Attribute</th>
        <th>Units</th>
        <th># Values</th>
    </tr>
    </thead>
    <tbody>
    @foreach($activityAttributes as $name => $attrs)
        <tr>
            <td>{{$name}}</td>
            <td>{{$units($attrs)}}</td>
            <td>{{$attrs->count()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>
<hr>
<br>

<h5>Samples Data Dictionary</h5>
<br>
<table id="entities-dd" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Attribute</th>
        <th>Units</th>
        <th># Values</th>
    </tr>
    </thead>
    <tbody>
    @foreach($entityAttributes as $name => $attrs)
        <tr>
            <td>{{$name}}</td>
            <td>{{$units($attrs)}}</td>
            <td>{{$attrs->count()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#activities-dd').DataTable({
                stateSave: true,
                pageLength: 100,
            });
            $('#entities-dd').DataTable({
                stateSave: true,
                pageLength: 100,
            });
        });
    </script>
@endpush
