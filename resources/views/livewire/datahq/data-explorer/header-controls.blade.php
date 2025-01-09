<span>
    <div class="col-lg-10 float-right" x-data="dataExplorerHeaderControls">
        <label>Select Data For:</label>
        <span wire:ignore id="select-data-for-wrapper">
        <select name="what" class="custom-select col-4 font-weight-bolder" id="select-data-for" title="Select Data For"
                data-container="#select-data-for-wrapper"
                data-style="btn-light no-tt">
            <option value="project" @selected($selectedData == "project")>Project</option>
            @foreach($experiments as $experiment)
                <option value="e-{{$experiment->id}}" @selected($selectedData == "e-{$experiment->id}")>Experiment: {{$experiment->name}}</option>
            @endforeach
        </select>
        </span>
        <label class="ml-4">Show:</label>
        <span wire:ignore id="view-data-wrapper">
        <select name="what" class="custom-select col-4 font-weight-bolder" title="View" id="view-data"
                data-container="#view-data-wrapper"
                data-style="btn-light no-tt">
            <option value="overview" @selected($selectedExplorer == "overview")>Overview</option>
            <option value="samples" @selected($selectedExplorer == "samples")>Samples Explorer</option>
            <option value="computations" @selected($selectedExplorer == "computations")>Computations
                Explorer
            </option>
            <option value="processes" @selected($selectedExplorer == "processes")>Processes Explorer
            </option>
        </select>
        </span>
        @script
            <script>
                document.addEventListener('livewire:navigating', () => {
                    // $("#select-data-for").selectpicker('destroy');
                    // $("#view-data").selectpicker('destroy');
                }, {once: true});

                Alpine.data("dataExplorerHeaderControls", () => {
                    return {
                        init() {
                            // $("#select-data-for").selectpicker('show');
                            // $("#view-data").selectpicker('show');
                            $('#select-data-for').on('change', function () {
                                let selected = $(this).val();
                                $wire.dispatch('selected-data', {selectedData: selected});
                            });

                            $('#view-data').on('change', function () {
                                let selected = $(this).val();
                                $wire.dispatch('selected-explorer', {selectedExplorer: selected});
                            });
                        }
                    }
                });
            </script>
        @endscript
    </div>
</span>
