<div>
    <div id="line-chart"></div>

    @push('scripts')
        <script>
            $(document).ready(() => {
                let e = document.getElementById('line-chart');
                Plotly.newPlot(e, [{
                    x: [1, 2, 3, 4, 5],
                    y: [1, 2, 4, 8, 16]
                }], {
                    title: "the chart title",
                    xaxis: {title: "x attr"},
                    yaxis: {title: "y attr"},
                }, {
                    displaylogo: false,
                    responsive: true,
                    title: "the chart title",
                    xaxis: {title: "x attr"},
                    yaxis: {title: "y attr"},
                });
            });
        </script>
    @endpush
</div>