<div class="col-lg-10 float-right">
    <label>Select Data For:</label>
    <select name="what" class="selectpicker" id="select-data-for"
            data-style="btn-light no-tt">
        <option value="p:{{$project->id}}:de:state">Project</option>
        @foreach($experiments as $experiment)
            <option value="exp:{{$experiment->id}}:de:state">Experiment: {{$experiment->name}}</option>
        @endforeach
        <option value="ds:DS1:de:state">Dataset DS1</option>
        <option value="ds:DS2:de:state">Dataset DS2</option>
    </select>
    <label class="ml-4">Show:</label>
    <select name="what" class="selectpicker" title="View" id="view-data"
            data-style="btn-light no-tt">
        <option value="overview" @selected(Request::routeIs('projects.datahq.index'))>Overview</option>
        <option value="samples" @selected(Request::routeIs('projects.datahq.sampleshq.*'))>Samples Explorer</option>
        <option value="computations" @selected(Request::routeIs('projects.datahq.computationshq.*'))>Computations
            Explorer
        </option>
        <option value="processes" @selected(Request::routeIs('projects.datahq.processeshq.*'))>Processes Explorer
        </option>
    </select>

    @push('scripts')
        <script>
            $('#view-data').on('change', function () {
                let r = "";
                let selected = $(this).val();
                switch (selected) {
                    case 'overview':
                        r = "{{route('projects.datahq.index', [$project, 'tab' => 'samples'])}}";
                        break;
                    case 'samples':
                        r = "{!!route('projects.datahq.sampleshq.index', [$project, 'tab' => 'all-samples', 'subview' => 'all-samples'])!!}";
                        break;
                    case 'computations':
                        r = "{{route('projects.datahq.computationshq.index', [$project])}}";
                        break;
                    case 'processes':
                        r = "{{route('projects.datahq.processeshq.index', [$project])}}";
                        break;
                }

                if (r !== "") {
                    window.location.href = r;
                }
            });

            const selectDataForRoute = "{{route('projects.datahq.save-data-for', [$project])}}";
            $('#select-data-for').on('change', function () {
                let selected = $(this).val();
                let formData = new FormData();
                formData.append("data_for", selected);
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                axios.post(selectDataForRoute, formData, config);
            });
        </script>
    @endpush
</div>