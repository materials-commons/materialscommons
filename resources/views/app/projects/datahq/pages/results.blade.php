<div class="col-11">
    <label>Show Data As:</label>
    <select name="what" class="selectpicker"
            data-style="btn-light no-tt">
        <option value="table">Table</option>
        <option value="line_chart" selected>Line Chart</option>
        <option value="bar_chart">Bar Chart</option>
        <option value="scatter_chart">Scatter Chart</option>
    </select>

    <label class="ml-4">X:</label>
    <select name="x" class="selectpicker" data-style="btn-light no-tt">
        <option value="stress" selected>stress</option>
        <option value="strain">strain</option>
    </select>

    <label class="ml-4">Y:</label>
    <select name="Y" class="selectpicker" data-style="btn-light no-tt">
        <option value="stress">stress</option>
        <option value="strain" selected>strain</option>
    </select>
    {!! $chart->render() !!}
</div>