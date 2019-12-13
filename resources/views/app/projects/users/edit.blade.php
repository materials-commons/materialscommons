@extends('layouts.app')

@section('pageTitle', 'Modify project members')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Modify Members of Project {{$project->name}}
        @endslot

        @slot('body')
            <div class="row">
                <div class="col-6">
                    <h4>Select Users To Add</h4>
                    <table id="all-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <a href="{{route('users.show', [$user->id])}}">
                                        {{$user->name}}
                                    </a>
                                </td>
                                <td>{{$user->description}}</td>
                                <td>
                                    <a href="{{route('projects.users.add', [$project->id, $user->id])}}">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h4>Project Users</h4>
                    <table id="project-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->users as $puser)
                            <tr>
                                <td>
                                    <a href="{{route('projects.users.show', [$project, $puser])}}">{{$puser->name}}</a>
                                </td>
                                <td>{{$puser->description}}</td>
                                <td>
                                    @if($project->owner->id === $puser->id)
                                        (Project Owner)
                                    @else
                                        <a href="{{route('projects.users.remove', [$project->id, $puser->id])}}">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#all-users').DataTable({
                    stateSave: true,
                });

                $('#project-users').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
