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

        <a class="action-link float-right ml-4" href="#create-table-modal" data-toggle="modal">
        <i class="fa fas fa-table mr-2"></i> New Table
    </a>

        <a class="action-link float-right" href="#create-chart-modal" data-toggle="modal">
        <i class="fa fas fa-chart-area mr-2"></i> New Chart
    </a>

    <nav class="nav nav-pills mb-3">
        <a class="nav-link active no-underline rounded-pill" href="#">Table: Samples</a>
        <a class="nav-link no-underline rounded-pill" href="#">Scatter: stress, strain</a>
        <a class="nav-link no-underline rounded-pill" href="#">Histogram: temperature</a>
    </nav>

        <div class="modal fade" tabindex="-1" id="create-table-modal" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-nav">
                        <h5 class="modal-title help-color">Create New Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="help-color">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <x-datahq.create-table :project="$project"
                                               :process-attributes="$processAttributes"
                                               :sample-attributes="$sampleAttributes"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>

        <div class="modal fade" tabindex="-1" id="create-chart-modal" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-nav">
                        <h5 class="modal-title help-color">Create New Chart</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="help-color">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <x-datahq.create-chart :project="$project"
                                               :process-attributes="$processAttributes"
                                               :sample-attributes="$sampleAttributes"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>


        @if(!$showFilters)
            @push('scripts')
                <script>

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

                </script>
            @endpush
        @endif
</div>
