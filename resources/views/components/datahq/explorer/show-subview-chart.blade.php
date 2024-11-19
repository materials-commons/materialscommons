<div x-data="showSubviewChart" @add-data="onAddData">
    <br/>
    <x-modal :id="'sv-not-implemented'" :title="'Not Implemented Yet'">
        <x-slot:body>
            <h5>Not Implemented Yet</h5>
        </x-slot:body>
    </x-modal>
    <div class="form-group">
        <label class="ml-4">Chart Controls:</label>
        <div class="btn-group" role="group">
            <a class="action-link ml-3 cursor-pointer"
               @click.prevent="toggleShowChartDataControls()">
                <i class="fa fas fa-plus mr-2"></i>Add Data
            </a>
            <a class="action-link ml-4" href="#sv-not-implemented" data-toggle="modal">
                <i class="fa fas fa-eye mr-2"></i>View Data
            </a>
            <a class="action-link ml-4 cursor-pointer" @click.prevent="downloadChartData()">
                <i class="fa fas fa-download mr-2"></i>Download Chart Data
            </a>
            <a class="action-link ml-4" href="#sv-not-implemented" data-toggle="modal">
                <i class="fa fas fa-trash mr-2"></i>Delete Data
            </a>
            <a class="action-link ml-4" href="#sv-not-implemented" data-toggle="modal">
                <i class="fa fas fa-save mr-2"></i>Save Chart
            </a>
            <a class="action-link ml-4 cursor-pointer"
               @click.prevent="drawChart()">
                <i class="fa fas fa-redo mr-2"></i>Redraw
            </a>
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
        <x-datahq.charts.add-data-controls :sample-attributes="$sampleAttributes"
                                           :process-attributes="$processAttributes"
                                           :event-name="'add-data'"/>
    </div>
    <div class="row">
        <form class="ml-5">
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
    @push('scripts')
        <script>
            mcutil.onAlpineInit('showSubviewChart', () => {
                return {
                    chartData: [],
                    chartDataChoices: [],
                    projectId: "{{$project->id}}",

                    init() {
                        window.showSubviewChartCallback = this.showSubviewChartCallback;
                        $(document).ready(() => {
                            this.drawChart();
                        });
                    },

                    toggleShowChartDataControls() {
                        if (typeof window.addDataControlsComponent.resetControls === 'function') {
                            window.addDataControlsComponent.resetControls();
                        }
                        $("#chart-data-controls").toggle();
                    },

                    onAddData(event) {
                        if (typeof window.addDataControlsComponent.resetControls === 'function') {
                            window.addDataControlsComponent.resetControls();
                        }

                        let formData = new FormData();
                        formData.append('xattr', event.detail.data.xAttr);
                        formData.append('xattr_type', event.detail.data.xAttrType);
                        formData.append('yattr', event.detail.data.yAttr);
                        formData.append('yattr_type', event.detail.data.yAttrType);
                        formData.append('filters', event.detail.data.filters);
                        let config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        };

                        this.chartDataChoices.push({
                            xattr: event.detail.data.xAttr,
                            xattr_type: event.detail.data.xAttrType,
                            yattr: event.detail.data.yAttr,
                            yattr_type: event.detail.data.yAttrType,
                            filters: event.detail.data.filters
                        });
                        let r = route('projects.datahq.sampleshq.get-chart-data', {
                            project: "{{$project->id}}",
                        });
                        axios.post(r, formData, config).then(resp => {
                            let x = [];
                            let y = [];
                            let text = [];
                            let experimentIds = [];
                            resp.data.forEach(function (item) {
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
                            this.drawChart();
                        });
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

                    drawChart() {
                        let e = document.getElementById('scatter-chart');
                        let chartTitle = $("#chart-title").val();
                        let xAxisTitle = $("#x-axis-title").val();
                        let yAxisTitle = $("#y-axis-title").val();

                        Plotly.purge(e);
                        Plotly.newPlot(e, this.chartData, {
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
                    },
                }
            });
        </script>
    @endpush
</div>
