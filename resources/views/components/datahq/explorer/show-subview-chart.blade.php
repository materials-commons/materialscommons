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
            function showSubviewChart() {
                return {
                    chartData: [],
                    init() {
                        window.showSubviewChartCallback = this.showSubviewChartCallback;
                        $(document).ready(() => {
                            this.drawChart();
                        });
                    },

                    onAddData2(event) {
                        console.log('onAddData received', event.detail);
                    },

                    toggleShowChartDataControls() {
                        if (typeof window.addDataControlsComponent.resetControls === 'function') {
                            window.addDataControlsComponent.resetControls();
                        }
                        $("#chart-data-controls").toggle();
                    },

                    onAddData(event) {
                        console.log("chartDataCallback called", event);
                        if (typeof window.addDataControlsComponent.resetControls === 'function') {
                            window.addDataControlsComponent.resetControls();
                        }

                        let formData = new FormData();
                        formData.append('xattr', event.detail.data.xAttr);
                        formData.append('xattr_type', event.detail.data.xAttrType);
                        formData.append('yattr', event.detail.data.yAttr);
                        formData.append('yattr_type', event.detail.data.yAttrType);
                        let config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        };

                        let r = route('projects.datahq.sampleshq.get-chart-data', {
                            project: "{{$project->id}}",
                        });
                        axios.post(r, formData, config).then(resp => {
                            console.log("get-chart-data response:", resp);
                            console.log("this.chartData = ", this.chartData);
                            let x = [];
                            let y = [];
                            let text = [];
                            resp.data.forEach(function (item) {
                                x.push(item.x);
                                y.push(item.y);
                                text.push(item.entity);
                            });
                            this.chartData.push({
                                x: x,
                                y: y,
                                text: text,
                                mode: 'markers',
                                type: 'scatter',
                            });
                            this.drawChart();
                        });
                    },

                    drawChart() {
                        let e = document.getElementById('scatter-chart');
                        let chartTitle = $("#chart-title").val();
                        let xAxisTitle = $("#x-axis-title").val();
                        let yAxisTitle = $("#y-axis-title").val();
                        //[
                        // {
                        //     x: [1, 2, 3, 4, 5],
                        //     y: [1, 6, 3, 6, 1],
                        //     mode: 'markers',
                        //     type: 'scatter',
                        //     marker: {size: 12},
                        // },
                        // {
                        //     x: [20, 30, 40, 50],
                        //     y: [10, 20, 70, 80],
                        //     type: 'line'
                        // }
                        //],

                        console.log("drawChart chartData = ", this.chartData);
                        Plotly.purge(e);
                        Plotly.newPlot(e, this.chartData, {
                            title: chartTitle,
                            xaxis: {title: xAxisTitle},
                            yaxis: {title: yAxisTitle},
                        }, {
                            displaylogo: false,
                            responsive: true,
                        });
                    },
                }
            }
        </script>
    @endpush
</div>
