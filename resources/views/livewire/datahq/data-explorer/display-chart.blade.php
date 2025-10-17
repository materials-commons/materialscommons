<div x-data="displayChart">
    <br/>
    <x-modal :id="'sv-not-implemented'" :title="'Not Implemented Yet'">
        <x-slot:body>
            <h5>Not Implemented Yet</h5>
        </x-slot:body>
    </x-modal>
    <div class="mb-3">
        <label class="ms-4">Chart Controls:</label>
        <div class="btn-group" role="group">
            <a class="action-link ms-3 cursor-pointer"
               @click.prevent="toggleShowChartDataControls()">
                <i class="fa fas fa-plus me-2"></i>Add Data
            </a>
            <a class="action-link ms-4" href="#sv-not-implemented" data-bs-toggle="modal">
                <i class="fa fas fa-eye me-2"></i>View Data
            </a>
            {{--            <a class="action-link ms-4 cursor-pointer" @click.prevent="downloadChartData()">--}}
            <a class="action-link ms-4 cursor-pointer" href="#sv-not-implemented" data-bs-toggle="modal">
                <i class="fa fas fa-download me-2"></i>Download Chart Data
            </a>
            <a class="action-link ms-4" href="#sv-not-implemented" data-bs-toggle="modal">
                <i class="fa fas fa-trash me-2"></i>Delete Data
            </a>
            <a class="action-link ms-4" href="#sv-not-implemented" data-bs-toggle="modal">
                <i class="fa fas fa-save me-2"></i>Save Chart
            </a>
            {{--            <a class="action-link ms-4 cursor-pointer"--}}
            {{--               @click.prevent="drawChart()">--}}
            {{--                <i class="fa fas fa-redo me-2"></i>Redraw--}}
            {{--            </a>--}}
        </div>
    </div>
    <div style="display:none">
        <form id="download-data" action="{{route('projects.datahq.sampleshq.download-chart-data', [$project])}}"
              method="post">
            @csrf
            <input id="dl-xattr" name="xattr" type="hidden"/>
            <input id="dl-xattr-type" name="xattr_type" type="hidden"/>
            <input id="dl-yattr" name="yattr" type="hidden"/>
            <input id="dl-yattr-type" name="yattr_type" type="hidden"/>
            <input id="dl-filters" name="filters" type="hidden"/>
        </form>
    </div>
    <div class="row" id="chart-data-controls" style="display: none">
        <livewire:datahq.data-explorer.data-controls :process-attributes="$processAttributes"
                                                     :sample-attributes="$sampleAttributes"/>
    </div>
    <div class="row">
        <form class="ms-5">
            <div class="row">
                <div class="col">
                    <input type="text" id="chart-title" class="form-control" placeholder="Chart Title..."/>
                </div>
                <div class="col">
                    <input type="text" id="x-axis-title" class="form-control" placeholder="X Axis Title..."/>
                </div>
                <div class="col">
                    <input type="text" id="y-axis-title" class="form-control" placeholder="Y Axis Title..."/>
                </div>
            </div>
        </form>
    </div>
    <div id="scatter-chart"></div>
    @script
    <script>
        Alpine.data('displayChart', () => {
            return {
                chartData: [],
                chartDataChoices: [],
                projectId: @js($project->id),

                init() {
                    let data = @js($chartData);
                    this.drawChart(data);
                },

                toggleShowChartDataControls() {
                    $("#chart-data-controls").toggle();
                },

                downloadChartData() {
                    let firstChart = this.chartDataChoices[0];
                    $("#dl-xattr").val(firstChart.xattr);
                    $("#dl-xattr-type").val(firstChart.xattr_type);
                    $("#dl-yattr").val(firstChart.yattr);
                    $("#dl-yattr-type").val(firstChart.yattr_type);
                    $("#dl-filters").val(firstChart.filters);
                    document.getElementById('download-data').submit();
                },

                loadChartData(d) {
                    console.log("d = ", d);
                    let data = d;
                    let x = [];
                    let y = [];
                    let text = [];
                    let experimentIds = [];
                    Object.entries(data).forEach(function ([key, item]) {
                        x.push(item.x);
                        y.push(item.y);
                        text.push(item.entity);
                        experimentIds.push(item.experiment_id);
                    });
                    this.chartData.push({
                        x: x,
                        y: y,
                        text: text,
                        experimentIds: experimentIds,
                        mode: 'markers',
                        type: 'scatter',
                    });
                },

                drawChart(data) {
                    if (data !== null) {
                        this.loadChartData(data);
                    }
                    let chartData = this.chartData;
                    setTimeout(() => {
                        // if (typeof window.addDataControlsComponent.resetControls === 'function') {
                        //     window.addDataControlsComponent.resetControls();
                        // }
                        let e = document.getElementById('scatter-chart');
                        let chartTitle = $("#chart-title").val();
                        let xAxisTitle = $("#x-axis-title").val();
                        let yAxisTitle = $("#y-axis-title").val();

                        Plotly.purge(e);
                        Plotly.newPlot(e, chartData, {
                            title: chartTitle,
                            xaxis: {title: xAxisTitle},
                            yaxis: {title: yAxisTitle},
                        }, {
                            displaylogo: false,
                            responsive: true,
                        });

                        e.on('plotly_click', function (data) {
                            let point = data.points[0];
                            let pointIndex = point.pointIndex;
                            let experimentId = point.data.experimentIds[pointIndex + 1];
                            let entity = point.data.text[pointIndex + 1];
                            let r = route('projects.experiments.entities.by-name.spread', {
                                project: "{{$project->id}}",
                                experiment: experimentId,
                                name: entity,
                            });
                            window.open(r, '_blank');
                        });
                    });
                },
            }
        });
    </script>
    @endscript
</div>

