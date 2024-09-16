<div class="col-12">
    <div class="form-group float-right">
        <label>Views:</label>
        <select name="" class="selectpicker" data-style="btn-light no-tt" id="existing-views"
                title="existing views"
                data-live-search="true">
            <option value="all-samples" @selected(Request::routeIs('projects.datahq.sampleshq.index'))>All
                Samples
            </option>
            <option value="sc: stress, strain">Scatter: stress, strain</option>
            <option value="hc: time, temperature">Histogram: time, temperature</option>
        </select>
    </div>

    <br/>
    <br/>
    <div class="row">
        @if($showFilters)
            <div class="form-group">
                <label>Add Filter On:</label>
                <select name="filteron" class="selectpicker" title="Add Filter" id="filter-on"
                        data-style="btn-light no-tt">
                    {{--                <option value="samples">Samples</option>--}}
                    <option
                        value="processes" @selected(Request::routeIs('projects.datahq.sampleshq.activities.filters'))>
                        Processes
                    </option>
                    <option
                        value="sample-attributes" @selected(Request::routeIs('projects.datahq.sampleshq.entity-attributes.filters'))>
                        Sample Attributes
                    </option>
                    <option
                        value="process-attributes" @selected(Request::routeIs('projects.datahq.sampleshq.activity-attributes.filters'))>
                        Process Attributes
                    </option>
                </select>
            </div>
        @endif
        <div class="form-group">
            @if($showFilters)
                <label class="ml-4">View Data As:</label>
            @else
                <label>View Data As:</label>
            @endif
            <select name="what" class="selectpicker" title="Type of View (Table/Chart)" id="view-as"
                    data-style="btn-light no-tt">
                <option value="close">(X) Close</option>
                <option value="table">Table</option>
                <option value="line-chart">Line Chart</option>
                <option value="bar-chart">Bar Chart</option>
                <option value="scatter-chart">Scatter Chart</option>
            </select>
        </div>
    </div>

    <div class="rowx mt-2" id="chart-controls" style="display: none">
        <div class="form-group">
            <label>Sample X:</label>
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
    </div>

    <div class="rowx mt-2" id="table-controls" style="display: none">
        <div class="form-group">
            <label>Sample Attributes</label>
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

    @push('scripts')
        <script>
            $('#filter-on').on('change', function () {
                let selected = $(this).val();
                let r = "";
                switch (selected) {
                    case 'processes':
                        r = "{{route('projects.datahq.sampleshq.activities.filters', [$project])}}";
                        break;
                    case 'sample-attributes':
                        r = "{{route('projects.datahq.sampleshq.entity-attributes.filters', [$project])}}";
                        break;
                    case 'process-attributes':
                        r = "{{route('projects.datahq.sampleshq.activity-attributes.filters', [$project])}}";
                        break;
                }

                if (r !== "") {
                    window.location.href = r;
                }
            });

            $('#existing-views').on('change', function () {
                let selected = $(this).val();
                if (selected === 'all-samples') {
                    window.location.href = "{{route('projects.datahq.sampleshq.index', [$project])}}"
                }
            });

            $("#view-as").on('change', function () {
                let selected = $(this).val();
                switch (selected) {
                    case 'table':
                        $('#table-controls').show();
                        $('#chart-controls').hide();
                        break;
                    case 'close':
                        $('#table-controls').hide();
                        $('#chart-controls').hide();
                        $(this).val('');
                        $(this).selectpicker('deselectAll');
                        break;
                    default:
                        $('#table-controls').hide();
                        $('#chart-controls').show();
                        break;
                }
            });
        </script>
    @endpush
</div>
