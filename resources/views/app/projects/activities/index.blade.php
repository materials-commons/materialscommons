@extends('layouts.app')

@section('pageTitle', 'Processes')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.activities.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Processes
            {{--            <a class="action-link float-right" href="#">--}}
            {{--                <i class="fas fa-plus mr-2"></i>Create Process--}}
            {{--            </a>--}}
        @endslot

        @slot('body')
            <br>
            <table id="activities" class="table table-hover" width="100%">
                <thead>
                <th>Name</th>
                <th>Samples</th>
                </thead>
                <tbody>
                @foreach($activityEntities as $name => $entities)
                    @if($name != "")
                        <tr>
                            <td>
                                <a href="{{route('projects.activities.show-by-name', [$project, $name])}}">{{$name}}</a>
                            </td>
                            <td>
                                @foreach($entities as $entity)
                                    <a href="{{route('projects.entities.show', [$project, $entity->id])}}">
                                        {{$entity->name}}
                                    </a>
                                    {{!$loop->last ? ", " : ""}}
                                @endforeach
                            </td>
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
                let projectId = "{{$project->id}}";
                $('#activities').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
