<div>
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
            <th>Filter/Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach($entityAttributes as $name => $attrs)
            <tr>
                <td>{{$name}}</td>
                <td>{{$units($attrs)}}</td>
                <td>{{$min($attrs)}}</td>
                <td>{{$max($attrs)}}</td>
                {{--            <td>{{$median($attrs)}}</td>--}}
                {{--            <td>{{$average($attrs)}}</td>--}}
                {{--            <td>{{$mode($attrs)}}</td>--}}
                <td>{{$attrs->count()}}</td>
                @php
                    $attrVals = $attrs->pluck('val')->map(function ($val) {
                                    $decoded = json_decode($val, true);
                                    return $decoded["value"];
                 });
                @endphp
                <td>
                    <a class="action-link" onclick='clickHandler(event, "entity", "{{$name}}", @json($attrVals))'>
                        <i class="fas fa-fw fa-filter"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@^4"></script>
        <script src="https://cdn.jsdelivr.net/npm/luxon@^2"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@^1"></script>
        <script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>
        <script>
            var table;
            $(document).ready(() => {
                table = $('#entities-dd').DataTable({
                    pageLength: 100,
                    stateSave: true
                });
            });

            let projectId = "{{$project->id}}";

            function clickHandler(e, attrType, attrName, data) {
                let tr = e.target.closest('tr');
                let row = table.row(tr);
                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    let r = route('projects.datahq.qb-attribute-details', {
                        project: projectId,
                        attrType: attrType,
                        attrName: attrName
                    });
                    axios.get(r).then((r) => {
                        let firstLetter = attrName.charAt(0);
                        let labels = [];
                        for (let i = 0; i < data.length; i++) {
                            labels.push('');
                        }
                        row.child(r.data).show();
                        let element = document.getElementById(`chart-${attrName}-${attrType}`);
                        console.log(element);
                        new Chart(element, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: attrName,
                                        data: data
                                    }
                                ]
                            }
                        });
                    });
                }
            }
        </script>
    @endpush

</div>