@extends('layouts.app')

@section('pageTitle', 'Add Datasets To Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Community: {{$community->name}}
        @endslot

        @slot('body')
            <table id="datasets" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Selected</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasets as $dataset)
                    <tr>
                        <td>
                            <a href="{{route('public.datasets.show', [$dataset])}}">
                                <i class="fa-fw fas me-2 fa-file"></i>{{$dataset->name}}
                            </a>
                        </td>
                        <td>{{$dataset->summary}}</td>
                        <td>
                            <div class="mb-3 form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$dataset->uuid}}"
                                       {{$dataset->communities->contains($community->id) ? 'checked' : ''}}
                                       onclick="updateSelection({{$dataset}}, this)">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="float-right">
                <a href="{{route('communities.edit', [$community])}}" class="action-link">Done</a>
            </div>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        let route = "{{route('api.communities.datasets.selection', [$community])}}";
        let apiToken = "{{$user->api_token}}";

        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });

        function updateSelection(dataset) {
            axios.put(`${route}?api_token=${apiToken}`, {
                dataset_id: dataset.id,
            });
        }
    </script>
@endpush
