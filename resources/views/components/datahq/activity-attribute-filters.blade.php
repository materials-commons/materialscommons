<div>
    <h5>Process Attributes</h5>
    <br/>
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
            <th>Add Filter</th>
        </tr>
        </thead>
        <tbody>
        @foreach($activityAttributes as $name => $attrs)
            <tr>
                <td>{{$name}}</td>
                <td>{{$units($attrs)}}</td>
                <td>{{$min($attrs)}}</td>
                <td>{{$max($attrs)}}</td>
                {{--            <td>{{$median($attrs)}}</td>--}}
                {{--            <td>{{$average($attrs)}}</td>--}}
                {{--            <td>{{$mode($attrs)}}</td>--}}
                <td>{{$attrs->count()}}</td>
                <td>
                    <a class="action-link cursor-pointer"
                       onclick="activityAttributeClickHandler(event, 'activity', '{{$name}}')">
                        <i class="fas fa-fw fa-filter"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#activities-dd').DataTable({
                    pageLength: 100,
                    stateSave: true
                });
            });

            function activityAttributeClickHandler(e, attrType, attrName) {
                let table = $('#activities-dd').DataTable();
                let projectId = "{{$project->id}}";
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
                        row.child(r.data).show();

                        let element = document.getElementById(`chart-${attrName}-${attrType}`);
                        let chartValues = element.getAttribute('data-chart-values');
                        let data = JSON.parse(chartValues);

                        let nbins = 20;
                        // if (data.length < 18) {
                        //     nbins = data.length + 2;
                        // }

                        Plotly.newPlot(element, [{
                            x: data,
                            type: 'histogram',
                            nbinsx: nbins,
                        }], {
                            title: attrName,
                            xaxis: {
                                rangeslider: {visible: true},
                            },
                            yaxis: {
                                fixedrange: true,
                                rangemode: 'tozero',
                            }
                        });
                    });
                }
            }
        </script>
    @endpush

</div>