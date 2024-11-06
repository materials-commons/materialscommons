<div class="col-12">
    @if($showFilters)
        <x-datahq.mql-controls :project="$project"/>
    @else
        <div class="form-group" x-data="datahqViewControls">
            <label class="ml-4">Show:</label>
            <div class="btn-group" role="group">
                <a class="action-link ml-3 cursor-pointer" @click.prevent="toggleProcesses()">
                    <i class="fa fas fa-code-branch mr-2"></i>Processes
                </a>
                <a class="action-link ml-4 cursor-pointer" @click.prevent="toggleSampleAttributes()">
                    <i class="fa fas fa-cubes mr-2"></i>Sample Attributes
                </a>
                <a class="action-link ml-4 cursor-pointer" @click.prevent="toggleProcessAttributes()">
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

    <x-datahq.create-table :modal-id="'create-table-modal'"
                           :project="$project"
                           :process-attributes="$processAttributes"
                           :sample-attributes="$sampleAttributes"/>

    <x-datahq.create-chart-modal :modal-id="'create-chart-modal'"
                                 :project="$project"
                                 :tab="$tab"
                                 :state-service="$stateService"
                                 :process-attributes="$processAttributes"
                                 :sample-attributes="$sampleAttributes"/>

    @if(!$showFilters)
        @push('scripts')
            <script>
                mcutil.onAlpineInit("datahqViewControls", () => {
                    return {
                        toggleProcesses() {
                            $("#activity-filters").toggle();
                            $('#entity-attribute-filters').hide();
                            $('#activity-attribute-filters').hide();
                        },

                        toggleSampleAttributes() {
                            $("#activity-filters").hide();
                            $('#entity-attribute-filters').toggle();
                            $('#activity-attribute-filters').hide();
                        },

                        toggleProcessAttributes() {
                            $("#activity-filters").hide();
                            $('#entity-attribute-filters').hide();
                            $('#activity-attribute-filters').toggle();
                        }
                    }
                })
            </script>
        @endpush
    @endif
</div>
