@extends('layouts.app')

@section('pageTitle', "{$project->name} - Data Explorer")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')

    <x-card>
        <x-slot:header>
            Data Explorer
            <x-datahq.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            <hr>
            <div class="mt-2">
                Computations not implemented
            </div>
        </x-slot:body>
    </x-card>

    @push('scripts')
        <script>
            $('#dhq_page').on('change', function () {
                let r = "";
                let selected = $(this).val();
                switch (selected) {
                    case 'samples':
                        break;
                    case 'computations':
                        break;
                    case 'processes':
                        r = "{{route('projects.datahq.index', [$project])}}";
                        break;
                    case 'activities':
                        break;
                    case 'sample_attributes':
                        r = "{{route('projects.datahq.entities', [$project])}}";
                        break;
                    case 'process_attributes':
                        break;
                    case 'computation_attributes':
                        break;
                    case 'activity_attributes':
                        break;
                    case 'results':
                        r = "{{route('projects.datahq.results', [$project])}}";
                        break;
                }
                if (r !== "") {
                    window.location.href = r;
                }
            });

            const selectDataForRoute = "{{route('projects.datahq.save-data-for', [$project])}}";
            $('#select-data-for').on('change', function () {
                let selected = $(this).val();
                console.log("selected = ", selected);
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

@stop
