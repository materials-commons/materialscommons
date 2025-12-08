<div class="col-12" x-data="datahqMqlControls">
    <h4>Filter Controls</h4>
    <div class="row col-12">
        @php
            $filters = "";
        @endphp
        <div class="col-8">
            <form>
                <div class="row">
                    <div class="mb-3 col-12">
                        <label for="mql">Filter:</label>
                        <textarea class="form-control col-12" id="mql" placeholder="Filter by..."
                                  rows="{{line_count($filters, 2)+1}}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4">
            <div class="row col-12">
                <a class="btn btn-danger" href="#">Reset</a>
                <a class="btn btn-warning ms-2" href="#">Save</a>
                <a class="btn btn-success ms-2" href="#">Run</a>
            </div>

            <div class="row col-12">
                <select name="what" class="form-select mt-4" title="Load Saved Filter">
                    <option value="proj">Annealed Samples</option>
                    <option value="proj">Stress vs Strain</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <label class="ms-4">Show/Add Filter On:</label>
            <div class="btn-group" role="group">
                <a class="action-link ms-3 cursor-pointer" @click.prevent="toggleProcesses()">
                    <i class="fa fas fa-code-branch me-2"></i>Processes</a>
                <a class="action-link ms-4 cursor-pointer" @click.prevent="toggleSampleAttributes()">
                    <i class="fa fas fa-cubes me-2"></i>Sample Attributes</a>
                <a class="action-link ms-4 cursor-pointer" @click.prevent="toggleProcessAttributes()">
                    <i class="fa fas fa-project-diagram me-2"></i>Process Attributes</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 border rounded" id="activity-filters" style="display: none">
            <br/>
            <x-datahq.activity-filters :project="$project"/>
            <br/>
        </div>

        <div class="col-12 border rounded" id="entity-attribute-filters" style="display: none">
            <br/>
            <x-datahq.entity-attribute-filters :project="$project"/>
            <br/>
        </div>

        <div class="col-12 border rounded" id="activity-attribute-filters" style="display: none">
            <br/>
            <x-datahq.activity-attribute-filters :project="$project"/>
            <br/>
        </div>
    </div>
    <hr/>
    @push('scripts')
        <script>
            mcutil.onAlpineInit("datahqMqlControls", function () {
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
            });
        </script>
    @endpush
</div>
