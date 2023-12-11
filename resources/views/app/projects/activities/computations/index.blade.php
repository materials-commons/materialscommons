@extends('layouts.app')

@section('pageTitle', "{$project->name} - Computations")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.activities.computations.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Computations
        @endslot

        @slot('body')
            <br>
            <table id="activities" class="table table-hover" width="100%">
                <thead>
                <th>Name</th>
                <th>Type</th>
                <th>Owner</th>
                </thead>
                <tbody>
                @foreach($activities as $activity)
                    @if($activity->name != "")
                        <tr>
                            <td>
                                <a href="{{route('projects.activities.computations.show', [$project, $activity])}}">
                                    {{$activity->name}}
                                </a>
                            </td>
                            <td>{{$activity->atype}}</td>
                            <td>{{$activity->owner->name}}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#activities').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
