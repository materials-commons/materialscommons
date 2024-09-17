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
                {{--                <option value="samples">Samples</option>--}}
                <option
                        value="processes">
                    Processes
                </option>
                <option
                        value="sample-attributes">
                    Sample Attributes
                </option>
                <option
                        value="process-attributes">
                    Process Attributes
                </option>
            </select>
        </div>
    </div>
    <div class="row">

    </div>
    <hr/>
    @push('scripts')
        <script>
            $('#filter-on').on('change', function () {
                let selected = $(this).val();
                let r = "";
                switch (selected) {
                    case 'processes':
                        {{--r = "{{route('projects.datahq.sampleshq.activities.filters', [$project])}}";--}}
                            break;
                    case 'sample-attributes':
                        {{--r = "{{route('projects.datahq.sampleshq.entity-attributes.filters', [$project])}}";--}}
                            break;
                    case 'process-attributes':
                        {{--r = "{{route('projects.datahq.sampleshq.activity-attributes.filters', [$project])}}";--}}
                            break;
                }
            });
        </script>
    @endpush
</div>