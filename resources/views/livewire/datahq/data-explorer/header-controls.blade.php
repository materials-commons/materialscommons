<span>
    <div class="col-lg-10 float-right" x-data="dataExplorerHeaderControls">
        <label>Select Data For:</label>
        <span wire:ignore id="select-data-for-wrapper">
        <select name="what" class="selectpicker" id="select-data-for" data-container="#select-data-for-wrapper"
                title="Select Data For"
                data-style="btn-light no-tt">
            <option value="project" selected>Project</option>
            @foreach($experiments as $experiment)
                <option value="e-{{$experiment->id}}">Experiment: {{$experiment->name}}</option>
            @endforeach
        </select>
    </span>
        <label class="ml-4">Show:</label>
        <span wire:ignore id="view-data-wrapper">
        <select name="what" class="selectpicker" title="View" id="view-data" data-container="#view-data-wrapper"
                data-style="btn-light no-tt">
            <option value="overview" @selected(Request::routeIs('projects.datahq.index'))>Overview</option>
            <option value="samples" @selected(Request::routeIs('projects.datahq.sampleshq.*'))>Samples Explorer</option>
            <option value="computations" @selected(Request::routeIs('projects.datahq.computationshq.*'))>Computations
                Explorer
            </option>
            <option value="processes" @selected(Request::routeIs('projects.datahq.processeshq.*'))>Processes Explorer
            </option>
        </select>
    </span>
        @push('scripts')
            <script>
                mcutil.onAlpineInit("dataExplorerHeaderControls", () => {
                    return {
                        init() {
                            $("#select-data-for").selectpicker('refresh');
                            $("#view-data").selectpicker('refresh');
                            let $wire = this.$wire;
                            $('#select-data-for').on('change', function () {
                                let selected = $(this).val();
                                console.log('select-data-for changed to: ', selected);
                                $wire.dispatch('selected-data', {selectedData: selected});
                            });

                            $('#view-data').on('change', function () {
                                let selected = $(this).val();
                                console.log('view-data changed to: ', selected);
                                $wire.dispatch('selected-view', {selectedView: selected});
                            });
                        }
                    }
                });
            </script>
        @endpush
    </div>
</span>