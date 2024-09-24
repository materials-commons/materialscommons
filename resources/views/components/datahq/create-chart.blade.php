<div>
    <div class="mt-2 ml-4" id="chart-controls">
        <div class="row">
            <div class="form-group">
                <input type="text" class="form-control" id="chart-name" placeholder="Enter chart name..."/>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label>Chart Type:</label>
                <select name="chart_type" class="selectpicker" data-style="btn-light no-tt" id="chart-type"
                        data-live-search="true" title="Select chart type...">
                    <option value="histogram">Histogram</option>
                    <option value="line">Line</option>
                    <option value="scatter">Scatter</option>
                </select>
            </div>
        </div>
        <div class="row" style="display: none" id="x-attr-select">
            <div class="form-group">
                <label>Select X Attribute Type:</label>
                <select name="x_attribute_type" class="selectpicker" data-style="btn-light no-tt" id="x-attr-type"
                        title="select X attribute type">
                    <option value="sample-attributes">Sample Attributes</option>
                    <option value="process-attributes">Process Attributes</option>
                </select>
            </div>
            <div id="show-x-sample-attrs" style="display: none">
                <div class="form-group">
                    <label class="ml-4">X:</label>
                    <select name="x" class="selectpicker" data-style="btn-light no-tt" id="x-sample-attrs"
                            data-live-search="true" title="x attribute">
                        @foreach($sampleAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="show-x-process-attrs" style="display: none">
                <div class="form-group">
                    <label class="ml-4">X:</label>
                    <select name="x" class="selectpicker" data-style="btn-light no-tt" id="x-process-attrs"
                            data-live-search="true" title="x attribute">
                        @foreach($processAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="display: none" id="y-attr-select">
            <div class="form-group">
                <label>Select Y Attribute Type: </label>
                <select name="y_attribute_type" class="selectpicker" data-style="btn-light no-tt"
                        id="y-attr-type" title="select Y attribute type">
                    <option value="sample-attributes">Sample Attributes</option>
                    <option value="process-attributes">Process Attributes</option>
                </select>
            </div>

            <div id="show-y-sample-attrs" style="display: none">
                <div class="form-group">
                    <label class="ml-4">Y:</label>
                    <select name="Y" class="selectpicker" data-style="btn-light no-tt" id="y-sample-attrs"
                            data-live-search="true" title="y attribute">
                        @foreach($sampleAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="show-y-process-attrs" style="display: none">
                <div class="form-group">
                    <label class="ml-4">Y:</label>
                    <select name="Y" class="selectpicker" data-style="btn-light no-tt" id="y-process-attrs"
                            data-live-search="true" title="y attribute">
                        @foreach($processAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <a class="btn btn-success ml-4" href="#">Create View</a>
    </div>

    @push('scripts')
        <script>

            $('#chart-type').on('change', function () {
                let selected = $(this).val();
                if (selected === 'histogram') {
                    $('#y-attr-select').show();
                    $('#x-attr-select').hide();
                } else {
                    $('#y-attr-select').show();
                    $('#x-attr-select').show();
                }
            });

            $('#x-attr-type').on('change', function () {
                let selected = $(this).val();
                if (selected === "sample-attributes") {
                    $("#show-x-sample-attrs").show();
                    $("#show-x-process-attrs").hide();
                } else {
                    $("#show-x-sample-attrs").hide();
                    $("#show-x-process-attrs").show();
                }
            });

            $('#y-attr-type').on('change', function () {
                let selected = $(this).val();
                if (selected === "sample-attributes") {
                    $("#show-y-sample-attrs").show();
                    $("#show-y-process-attrs").hide();
                } else {
                    $("#show-y-sample-attrs").hide();
                    $("#show-y-process-attrs").show();
                }
            });

            function handleCreateViewForChart(viewType) {
                let projectId = "{{$project->id}}";
                let xAttr = $("x-attr").val();
                let yAttr = $("y-attr").val();
                let chartType = $("#chart-type").val();
                let chartName = $("chart-name").val();
            }
        </script>
    @endpush
</div>

