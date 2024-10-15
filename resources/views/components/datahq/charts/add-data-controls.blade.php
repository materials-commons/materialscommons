<div class="mt-2 ml-4 col-12" id="chart-controls" x-data="addDataControlsComponent">
    <form id="chart-controls-form">
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
            <div class="form-group">
                <a class="btn btn-success ml-4 cursor-pointer disabled"
                   id="add-to-chart"
                   @click.prevent="handleAddToChart()"
                   roll="button" aria-disabled="true">Add To Chart</a>
            </div>
        </div>
        <div class="row" style="display: none" id="x-attr-select">
            <div class="form-group">
                <label>Select X Attribute Type:</label>
                <select name="x_attribute_type" class="selectpicker" data-style="btn-light no-tt" id="x-attr-type"
                        title="select X attribute type">
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
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
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
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
            <div id="show-y-process-attrs" style="display: none" class="col-8">
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
        <div class="row col-12x" style="display: none" id="show-chart-filters">
            <div class="form-group col-12">
                <label>Chart Data Filters:</label>
                <textarea class="form-control col-4" id="chart-filters" placeholder="Filters..."></textarea>
            </div>
        </div>

    </form>

    @push('scripts')
        <script>
            function addDataControlsComponent() {
                return {
                    eventName: "{{$eventName}}",
                    init() {
                        window.addDataControlsComponent = this;

                        $('#chart-type').on('change', function () {
                            let selected = $(this).val();
                            if (selected === 'histogram') {
                                $('#y-attr-select').show();
                                $('#x-attr-select').hide();
                            } else {
                                $('#y-attr-select').show();
                                $('#x-attr-select').show();
                            }

                            $("#show-chart-filters").show();
                        });

                        $('#x-attr-type').on('change', function () {
                            let selected = $(this).val();
                            if (selected === "sample") {
                                $("#show-x-sample-attrs").show();
                                $("#show-x-process-attrs").hide();
                            } else {
                                $("#show-x-sample-attrs").hide();
                                $("#show-x-process-attrs").show();
                            }
                        });

                        $('#y-process-attrs').on('change', function () {
                            $('#add-to-chart').removeClass("disabled");
                        });

                        $('#y-sample-attrs').on('change', function () {
                            $('#add-to-chart').removeClass("disabled");
                        });

                        $('#y-attr-type').on('change', function () {
                            let selected = $(this).val();
                            if (selected === "sample") {
                                $("#show-y-sample-attrs").show();
                                $("#show-y-process-attrs").hide();
                            } else {
                                $("#show-y-sample-attrs").hide();
                                $("#show-y-process-attrs").show();
                            }
                        });
                    },

                    handleAddToChart() {
                        console.log("handleAddToChart called:", this.eventName);
                        let xAttrType = $("#x-attr-type").val();
                        let xAttr = $("#x-sample-attrs").val();
                        if (xAttrType === 'process') {
                            xAttr = $("#x-process-attrs").val();
                        }

                        let yAttrType = $("#y-attr-type").val()
                        let yAttr = $("#y-sample-attrs").val();
                        if (yAttrType === 'process') {
                            yAttr = $("#y-process-attrs").val();
                        }
                        let chartType = $("#chart-type").val();
                        let filters = $("#chart-filters").val();
                        let data = {
                            xAttrType,
                            xAttr,
                            yAttrType,
                            yAttr,
                            chartType,
                            filters,
                        }
                        this.$dispatch(this.eventName, {
                            data,
                        });
                    },

                    resetControls() {
                        $('#chart-controls-form')[0].reset();
                        $("#chart-type").selectpicker('refresh');
                        $("#y-attr-type").selectpicker('refresh');
                        $("#y-sample-attrs").selectpicker('refresh');
                        $("#y-process-attrs").selectpicker('refresh');
                        $("#x-attr-type").selectpicker('refresh');
                        $("#x-sample-attrs").selectpicker('refresh');
                        $("#x-process-attrs").selectpicker('refresh');
                        $('#add-to-chart').addClass("disabled");
                        $('#y-attr-select').hide();
                        $('#x-attr-select').hide();
                        $("#show-x-sample-attrs").hide();
                        $("#show-x-process-attrs").hide();
                        $("#show-y-sample-attrs").hide();
                        $("#show-y-process-attrs").hide();
                        $("#chart-filters").val("");
                        $("#show-chart-filters").hide();
                    }
                }
            }
        </script>
    @endpush
</div>
