<div>
    <br/>
    <div class="form-group">
        <label class="ml-4">Chart Controls:</label>
        <div class="btn-group" role="group">
            <a class="action-link ml-3 cursor-pointer" onclick="toggleProcesses(event)">
                <i class="fa fas fa-plus mr-2"></i>Add Data
            </a>
            <a class="action-link ml-4 cursor-pointer" onclick="toggleSampleAttributes(event)">
                <i class="fa fas fa-trash mr-2"></i>Delete Data
            </a>
            <a class="action-link ml-4 cursor-pointer" onclick="toggleProcessAttributes(event)">
                <i class="fa fas fa-save mr-2"></i>Save Chart
            </a>
        </div>
    </div>
    <div class="row">
        <form class="ml-5">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Chart Title..."/>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="X Axis Title..."/>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Y Axis Title..."/>
                </div>
            </div>
        </form>
    </div>
    <div id="scatter-chart"></div>
    @push('scripts')
        <script>
            $(document).ready(() => {
                let e = document.getElementById('scatter-chart');
                Plotly.newPlot(e, [
                    {
                        x: [1, 2, 3, 4, 5],
                        y: [1, 6, 3, 6, 1],
                        mode: 'markers',
                        type: 'scatter',
                        marker: {size: 12},
                    },
                    {
                        x: [20, 30, 40, 50],
                        y: [10, 20, 70, 80],
                        type: 'line'
                    }
                ], {
                    title: "the chart title",
                    xaxis: {title: "x attr"},
                    yaxis: {title: "y attr"},
                }, {
                    displaylogo: false,
                    responsive: true,
                })
            });
        </script>
    @endpush
</div>