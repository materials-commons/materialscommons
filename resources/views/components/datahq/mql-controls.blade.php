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
            <label class="ml-4">Add Filter On:</label>
            <select name="filteron" class="selectpicker" title="Add Filter" id="filter-on"
                    data-style="btn-light no-tt">
                <option value="close">(X) Close</option>
                <option value="processes">Processes</option>
                <option value="sample-attributes">Sample Attributes</option>
                <option value="process-attributes">Process Attributes</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12" id="activity-filters" style="display: none">
            <x-datahq.activity-filters :project="$project"/>
        </div>

        <div class="col-12" id="entity-attribute-filters" style="display: none">
            <x-datahq.entity-attribute-filters :project="$project"/>
        </div>

        <div class="col-12" id="activity-attribute-filters" style="display: none">
            <x-datahq.activity-attribute-filters :project="$project"/>
        </div>
    </div>
    <hr/>
    @push('scripts')
        <script>
            $('#filter-on').on('change', function () {
                let selected = $(this).val();
                switch (selected) {
                    case 'processes':
                        $('#activity-filters').show();
                        $('#entity-attribute-filters').hide();
                        $('#activity-attribute-filters').hide();
                        break;
                    case 'sample-attributes':
                        $('#activity-filters').hide();
                        $('#entity-attribute-filters').show();
                        $('#activity-attribute-filters').hide();
                        break;
                    case 'process-attributes':
                        $('#activity-filters').hide();
                        $('#entity-attribute-filters').hide();
                        $('#activity-attribute-filters').show();
                        break;
                    case 'close':
                        $('#activity-filters').hide();
                        $('#entity-attribute-filters').hide();
                        $('#activity-attribute-filters').hide();
                        $(this).val('');
                        $(this).selectpicker('deselectAll');
                        break;
                }
            });
        </script>
    @endpush
</div>