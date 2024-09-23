<div class="col-12">
    <h4>Filter Controls</h4>
    <div class="row col-12">
        @php
            $filters = "";
        @endphp
        <div class="col-8">
            <form>
                <div class="form-row">
                    <div class="form-group col-12">
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
                <a class="btn btn-warning ml-2" href="#">Save</a>
                <a class="btn btn-success ml-2" href="#">Run</a>
            </div>

            <div class="row col-12">
                <select name="what" class="selectpicker mt-4" title="Load Saved Filter"
                        data-style="btn-light no-tt">
                    <option value="proj">Annealed Samples</option>
                    <option value="proj">Stress vs Strain</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label class="ml-4">Show/Add Filter On:</label>
            <div class="btn-group" role="group">
                <a class="action-link ml-3 cursor-pointer" onclick="toggleProcesses(event)">
                    <i class="fa fas fa-code-branch mr-2"></i>Processes</a>
                <a class="action-link ml-4 cursor-pointer" onclick="toggleSampleAttributes(event)">
                    <i class="fa fas fa-cubes mr-2"></i>Sample Attributes</a>
                <a class="action-link ml-4 cursor-pointer" onclick="toggleProcessAttributes(event)">
                    <i class="fa fas fa-project-diagram mr-2"></i>Process Attributes</a>
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
</div>