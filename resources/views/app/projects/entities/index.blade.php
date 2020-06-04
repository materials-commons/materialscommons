@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Samples
            {{--            <a class="action-link float-right" href="{{route('projects.entities-export', [$project])}}">--}}
            {{--                <i class="fas fa-download mr-2"></i>Download As Excel--}}
            {{--            </a>--}}

            <a class="action-link float-right" href="{{route('projects.entities.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i>Create Sample
            </a>
        @endslot

        @slot('body')
            <br>
            <table id="entities" class="table table-hover">
                <thead>
                <th>Name</th>
                @foreach($activities as $activity)
                    <th>{{$activity->name}}</th>
                @endforeach
                </thead>
                <tbody>
                @foreach($entities as $entity)
                    <tr>
                        <td>
                            <a href="{{route('projects.entities.show', [$project, $entity])}}">
                                {{$entity->name}}
                            </a>
                        </td>
                        @foreach($usedActivities[$entity->id] as $used)
                            @if($used)
                                <td>X</td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                let projectId = "{{$project->id}}";
                $('#entities').DataTable({
                    stateSave: true,
                    scrollX: true,
                });
            });
        </script>
    @endpush
@stop
