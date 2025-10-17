<div class="col-sm">
    <h4>In Process</h4>
    <table class="table table-sm table-borderless ms-4">
        <thead>
        <tr class="row">
            <th class="col-sm-1">Y</th>
            <th class="col-sm-1">N</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($activities as $activity)
            <tr class="row" id="{{$loop->index}}">
                <td class="col-sm-1">
                    <input type="checkbox" name="activities[]" value="{{$activity->name}}">
                </td>
                <td class="col-sm-1">
                    <input type="checkbox" name="activities[]" value="!{{$activity->name}}">
                </td>
                <td>{{$activity->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>