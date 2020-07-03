@component('components.card')
    @slot('header')
        Data Dictionary for {{$name}}
    @endslot

    @slot('body')
        <h5>Processes Data Dictionary</h5>
        <br>
        <table id="activities-dd" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Attribute</th>
                <th>Units</th>
                <th>Min</th>
                <th>Max</th>
                <th>Median</th>
                <th>Mode</th>
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
                    <td>{{$median($attrs)}}</td>
                    <td>{{$mode($attrs)}}</td>
                    <td>{{$numberOfMeasurements($attrs)}}</td>
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
                <th>Min</th>
                <th>Max</th>
                <th>Median</th>
                <th>Mode</th>
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
                    <td>{{$median($attrs)}}</td>
                    <td>{{$mode($attrs)}}</td>
                    <td>{{$numberOfMeasurements($attrs)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#activities-dd').DataTable({
                stateSave: true
            });
            $('#entities-dd').DataTable({
                stateSave: true
            });
        });
    </script>
@endpush