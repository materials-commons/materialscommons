<div>
    <br/>
    <div class="form-group">
        <label class="ml-4">Chart Controls:</label>
        <div class="btn-group" role="group">
            <a class="action-link ml-3 cursor-pointer" onclick="toggleShowChartDataControls()">
                <i class="fa fas fa-plus mr-2"></i>Add Data
            </a>
            <a class="action-link ml-4 cursor-pointer" onclick="">
                <i class="fa fas fa-trash mr-2"></i>Delete Data
            </a>
            <a class="action-link ml-4 cursor-pointer" onclick="">
                <i class="fa fas fa-save mr-2"></i>Save Chart
            </a>
            <a class="action-link ml-4 cursor-pointer" onclick="drawChart()">
                <i class="fa fas fa-redo mr-2"></i>Redraw
            </a>
        </div>
    </div>
    <div class="row" id="chart-data-controls" style="display: none">
        <x-datahq.charts.add-data-controls :sample-attributes="$sampleAttributes"
                                           :process-attributes="$processAttributes"
                                           :callback="'controlsCallback'"/>
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
            function toggleShowChartDataControls() {
                $("#chart-data-controls").toggle();
            }

            function controlsCallback() {
                console.log("controlsCallback called");
            }

            function drawChart() {
                let e = document.getElementById('scatter-chart');
                let chartTitle = $("#chart-title").val();
                let xAxisTitle = $("#x-axis-title").val();
                let yAxisTitle = $("#y-axis-title").val();

                Plotly.purge(e);
                Plotly.newPlot(e, [
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
                ], {
                    title: chartTitle,
                    xaxis: {title: xAxisTitle},
                    yaxis: {title: yAxisTitle},
                }, {
                    displaylogo: false,
                    responsive: true,
                })
            }

            $(document).ready(() => {
                drawChart();
            });
        </script>
    @endpush
</div>
