<div class="col-11">
    <br/>
    <h5>Create Data View</h5>
    <div class="row">
        <div class="form-group">
            <label>View Data As:</label>
            <select name="what" class="selectpicker" title="Type of View (Table/Chart)" id="view-as"
                    data-style="btn-light no-tt">
                <option value="table">Table</option>
                <option value="line-chart">Line Chart</option>
                <option value="bar-chart">Bar Chart</option>
                <option value="scatter-chart">Scatter Chart</option>
            </select>
        </div>

        <div class="form-group" id="chart-controls" style="display: none">
            <label class="ml-4">Sample X:</label>
            <select name="x" class="selectpicker" data-style="btn-light no-tt"
                    data-live-search="true" title="x attribute">
                @foreach($sampleAttributes as $attr)
                    <option value="{{$attr->name}}">s:{{$attr->name}}</option>
                @endforeach
                <option data-divider="true"></option>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">p:{{$attr->name}}</option>
                @endforeach
            </select>

            <label class="ml-4">Y:</label>
            <select name="Y" class="selectpicker" data-style="btn-light no-tt"
                    data-live-search="true" title="y attribute">
                @foreach($sampleAttributes as $attr)
                    <option value="{{$attr->name}}">s:{{$attr->name}}</option>
                @endforeach
                <option data-divider="true">--Processes--</option>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">p:{{$attr->name}}</option>
                @endforeach
            </select>

            <a class="btn btn-success" href="#">Create View</a>
        </div>

        <div class="form-group" id="table-controls" style="display: none">
            <label class="ml-4">Sample Attributes</label>
            <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-attributes"
                    data-live-search="true" data-actions-box="true" multiple>
                @foreach($sampleAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>

            <label class="ml-4">Process Attributes</label>
            <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-attributes"
                    data-live-search="true" data-actions-box="true" multiple>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>

            <a class="btn btn-success" href="#">Create View</a>
        </div>
    </div>

    <div class="row mt-2">
        <div class="form-group">
            <label>Show Existing Views:</label>
            <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-attributes"
                    title="existing views"
                    data-live-search="true">
                <option value="samples" selected>Samples</option>
                <option value="sc: stress, strain">Scatter: stress, strain</option>
                <option value="hc: time, temperature">Histogram: time, temperature</option>
            </select>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#table-attributes').on('change', function () {
            let selected = $(this).val();
        });

        let tableControlsShown = false;
        let chartControlsShow = false;

        $("#view-as").on('change', function () {
            let selected = $(this).val();
            switch (selected) {
                case 'table':
                    $('#table-controls').show();
                    $('#chart-controls').hide();
                    break;
                default:
                    $('#table-controls').hide();
                    $('#chart-controls').show();
                    break;
            }
        });
    </script>
@endpush