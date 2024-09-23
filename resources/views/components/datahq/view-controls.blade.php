<div class="col-12">
    @if($showFilters)
        <x-datahq.mql-controls :project="$project"/>
    @else
        <div class="form-group">
            <label class="ml-4">Show:</label>
            <div class="btn-group" role="group">
                <a class="action-link ml-3 cursor-pointer" onclick="toggleProcesses(event)">
                    <i class="fa fas fa-code-branch mr-2"></i>Processes
                </a>
                <a class="action-link ml-4 cursor-pointer" onclick="toggleSampleAttributes(event)">
                    <i class="fa fas fa-cubes mr-2"></i>Sample Attributes
                </a>
                <a class="action-link ml-4 cursor-pointer" onclick="toggleProcessAttributes(event)">
                    <i class="fa fas fa-project-diagram mr-2"></i>Process Attributes
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 border rounded" id="activity-filters" style="display: none">
                <br/>
                <x-projects.processes.processes-table :project="$project"/>
                <br/>
            </div>

            <div class="col-12 border rounded" id="entity-attribute-filters" style="display: none">
                <br/>
                <x-projects.samples.sample-attributes-table :project="$project"/>
                <br/>
            </div>

            <div class="col-12 border rounded" id="activity-attribute-filters" style="display: none">
                <br/>
                <x-projects.processes.process-attributes-table :project="$project"/>
                <br/>
            </div>
        </div>
        <hr/>
    @endif

    <a class="action-link float-right ml-4"
       href="{{route('projects.datahq.add-filtered-view', [$project, 'state-service' => 'sampleshq'])}}">
        <i class="fa fas fa-table mr-2"></i> New Table
    </a>

    <a class="action-link float-right"
       href="{{route('projects.datahq.add-filtered-view', [$project, 'state-service' => 'sampleshq'])}}">
        <i class="fa fas fa-chart-area mr-2"></i> New Chart
    </a>
    <nav class="nav nav-pills mb-3">
        <a class="nav-link active no-underline rounded-pill" href="#">Table: Samples</a>
        <a class="nav-link no-underline rounded-pill" href="#">Scatter: stress, strain</a>
        <a class="nav-link no-underline rounded-pill" href="#">Histogram: temperature</a>
    </nav>

    <div class="mt-2" id="chart-controls" style="display: none">
        <div class="form-group">
            <label>Sample X:</label>
            <select name="x" class="selectpicker" data-style="btn-light no-tt" id="x-attr"
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
            <select name="Y" class="selectpicker" data-style="btn-light no-tt" id="y-attr"
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

    <div class="mt-2" id="table-controls" style="display: none">
        <div class="form-group">
            <label>Sample Attributes</label>
            <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-sample-attributes"
                    data-live-search="true" data-actions-box="true" multiple>
                @foreach($sampleAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>

            <label class="ml-4">Process Attributes</label>
            <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-process-attributes"
                    data-live-search="true" data-actions-box="true" multiple>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>

            <a class="btn btn-success" onclick="handleCreateView(event)">Create View</a>
        </div>
    </div>

    @push('scripts')
        <script>
            function handleCreateView(e) {
                let viewType = $('#view-as').val();
                if (viewType === 'table') {
                    handleCreateViewForTable();
                } else {
                    handleCreateViewForChart(viewType);
                }
            }

            function handleCreateViewForTable() {
                let projectId = "{{$project->id}}";
                let processAttrs = $("#table-process-attributes").val();
                let sampleAttrs = $("#table-sample-attributes").val();
                let r = route('projects.datahq.sampleshq.create-table-view', {
                    project: projectId,
                });
                let formData = new FormData();
                formData.append("entityAttrs", sampleAttrs);
                formData.append("activityAttrs", processAttrs);
            }

            @if(!$showFilters)
            function toggleProcesses(e) {
                $("#activity-filters").toggle();
                $('#entity-attribute-filters').hide();
                $('#activity-attribute-filters').hide();
            }

            function toggleSampleAttributes(e) {
                $("#activity-filters").hide();
                $('#entity-attribute-filters').toggle();
                $('#activity-attribute-filters').hide();
            }

            function toggleProcessAttributes(e) {
                $("#activity-filters").hide();
                $('#entity-attribute-filters').hide();
                $('#activity-attribute-filters').toggle();
            }
            @endif

            function handleCreateViewForChart(viewType) {
                let x = $("x-attr").val();
                let y = $("y-attr").val();
            }

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
