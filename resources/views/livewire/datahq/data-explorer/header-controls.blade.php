<span>
    <div class="col-lg-10 float-right" x-data="dataExplorerHeaderControls">
        <label>Select Data For:</label>
        <select name="what" class="selectpicker" id="select-data-for" title="Select Data For"
                data-style="btn-light no-tt">
            <option value="project" @selected($selectedData == "project")>Project</option>
            @foreach($experiments as $experiment)
                <option value="e-{{$experiment->id}}" @selected($selectedData == "e-{{$experiment->id}}")>Experiment: {{$experiment->name}}</option>
            @endforeach
        </select>
        <label class="ml-4">Show:</label>
        <select name="what" class="selectpicker" title="View" id="view-data" data-style="btn-light no-tt">
            <option value="overview" @selected($selectedView == "overview")>Overview</option>
            <option value="samples" @selected($selectedView == "samples")>Samples Explorer</option>
            <option value="computations" @selected($selectedView == "computations")>Computations
                Explorer
            </option>
            <option value="processes" @selected($selectedView == "processes")>Processes Explorer
            </option>
        </select>
        @script
            <script>
                document.addEventListener('livewire:navigating', () => {
                    $("#select-data-for").selectpicker('destroy');
                    $("#view-data").selectpicker('destroy');
                }, {once: true});

                Alpine.data("dataExplorerHeaderControls", () => {
                    return {
                        init() {
                            $("#select-data-for").selectpicker('show');
                            $("#view-data").selectpicker('show');
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
        @endscript
    </div>
</span>