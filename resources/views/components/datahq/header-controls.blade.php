<div class="col-lg-10 float-right">
    <label>Select Data For:</label>
    <span wire:ignore id="select-data-for-wrapper">
        <select name="what" class="selectpicker" id="select-data-for" data-container="#select-data-for-wrapper"
                title="Select Data For"
                data-style="btn-light no-tt">
            <option value="p:{{$project->id}}" selected>Project</option>
            @foreach($experiments as $experiment)
                <option value="exp:{{$experiment->id}}">Experiment: {{$experiment->name}}</option>
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
            $('#view-data').on('change', function () {
                let r = "";
                let selected = $(this).val();
                switch (selected) {
                    case 'overview':
                        r = "{{route('projects.datahq.index', [$project, 'tab' => 'samples'])}}";
                        break;
                    case 'samples':
                        r = "{!!route('projects.datahq.sampleshq.index', [$project, 'tab' => 'index', 'subview' => 'index'])!!}";
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

            $('#select-data-for').on('change', function () {
                let selected = $(this).val();
                let splitValue = selected.split(":");

                let type = splitValue[0]; // 'p' or 'exp'
                let id = splitValue[1]; // respective IDs

            });

            {{--const selectDataForRoute = "{{route('projects.datahq.save-data-for', [$project])}}";--}}
            {{--$('#select-data-for').on('change', function () {--}}
            {{--    let selected = $(this).val();--}}
            {{--    let formData = new FormData();--}}
            {{--    formData.append("data_for", selected);--}}
            {{--    let config = {--}}
            {{--        headers: {--}}
            {{--            'Content-Type': 'multipart/form-data'--}}
            {{--        }--}}
            {{--    }--}}
            {{--    axios.post(selectDataForRoute, formData, config);--}}
            {{--});--}}
        </script>
    @endpush
</div>
