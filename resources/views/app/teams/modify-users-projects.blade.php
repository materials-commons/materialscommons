@extends('layouts.app')

@section('pageTitle', 'Create Team')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Team
        @endslot

        @slot('body')
            <div class="row">
                <div class="col-12">
                    <h4>Projects in team</h4>
                    <a class="action-link float-right mr-2" href="#">
                        <i class="fas fa-fw fa-plus"></i> Add Project
                    </a>
                    <br>
                    <table id="team-projects" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Owner</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($team->projects as $project)
                            <tr>
                                <td>{{$project->name}}</td>
                                <td>{{$project->summary}}</td>
                                <td>{{$project->owner->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-6">
                    <h4>Select Users To Add</h4>
                    <br>
                    <table id="all-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Affiliations</th>
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
                                <td>{{$user->affiliations}}</td>
                                <td>
                                    <a href="#">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h4>Team Users</h4>
                    <br>
                    <table id="team-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Affiliations</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($team->users as $puser)
                            <tr>
                                <td>
                                    <a href="{{route('projects.users.show', [$project, $puser])}}">{{$puser->name}}</a>
                                </td>
                                <td>{{$puser->affiliations}}</td>
                                <td>
                                    @if($project->owner->id === $puser->id)
                                        (Project Owner)
                                    @else
                                        <a href="#">
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

                $('#team-projects').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
