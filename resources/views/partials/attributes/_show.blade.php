@component('components.card')
    @slot('header')
        Attribute: {{$attributeName}}
    @endslot

    @slot('body')

        <h5>Attribute Statistics</h5>
        <br>
        <table class="bootstrap-table bootstrap-table-hover" style="width:100%">
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
            @foreach($attributeValues as $name => $attrs)
                <tr>
                    <td>{{$name}}</td>
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

        <br>
        <hr>
        <br>

        <h5>Attribute Values</h5>
        <br>

        <table id="attribute-values" class="bootstrap-table bootstrap-table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Value</th>
                <th>Unit</th>
                <th>From</th>
                {{--                <th>Samples</th>--}}
                {{--                <th>Processes</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($attributeValues as $name => $attrs)
                @foreach($attrs as $attr)
                    <tr>
                        <td>
                            @json($value($attr->val), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                        </td>
                        <td>{{$attr->unit}}</td>
                        {{--                        <td>Samples here</td>--}}
                        {{--                        <td>Processes here</td>--}}
                        <td>
                            @if($attr->object_type === 'entity')
                                <a href="{{route('projects.entities.show', [$project, $attr->object_id])}}">
                                    {{$attr->object_name}}
                                </a>
                            @else
                                <a href="{{route('projects.activities.show', [$project, $attr->object_id])}}">
                                    {{$attr->object_name}}
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#attribute-values').DataTable({
                stateSave: true
            });
        });
    </script>
@endpush