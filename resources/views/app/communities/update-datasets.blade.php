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
            <table id="datasets" class="table table-hover" style="width:100%" x-data="communitiesUpdateDatasets">
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
                                <i class="fa-fw fas mr-2 fa-file"></i>{{$dataset->name}}
                            </a>
                        </td>
                        <td>{{$dataset->summary}}</td>
                        <td>
                            <div class="form-group form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$dataset->uuid}}"
                                       {{$dataset->communities->contains($community->id) ? 'checked' : ''}}
                                       @click.prevent="updateSelection({{$dataset}}, this)">
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
        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });

        mcutil.onAlpineInit("communitiesUpdateDatasets", () => {
            return {
                route: "{{route('api.communities.datasets.selection', [$community])}}",
                apiToken: "{{$user->api_token}}",

                updateSelection(dataset) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        dataset_id: dataset.id,
                    });
                }
            }
        });
    </script>
@endpush
