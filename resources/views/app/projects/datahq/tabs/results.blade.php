<div style="width:75%;">
    <label>Show Data As:</label>
    <select name="what" class="selectpicker"
            data-style="btn-light no-tt">
        <option value="proj">Table</option>
        <option value="proj">Line Chart</option>
        <option value="proj">Bar Chart</option>
        <option value="proj">Scatter Chart</option>
    </select>
    {!! $chart->render() !!}
</div>