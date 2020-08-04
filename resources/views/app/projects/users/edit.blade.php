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
                                    <div class="dropdown">
                                        <a class="nav-link fs-11 ml-5 dropdown-toggle td-none" href="#" role="button"
                                           id="dropdownMenuLink"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa-fw fas fa-plus mr-2"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item td-none"
                                               href="{{route('projects.users.add', [$project, $user->id])}}">
                                                As User
                                            </a>
                                            <a class="dropdown-item td-none"
                                               href="{{route('projects.admins.add', [$project, $user->id])}}">
                                                As Admin
                                            </a>
                                        </div>
                                    </div>
                                    {{--                                    <a href="{{route('projects.users.add', [$project->id, $user->id])}}">--}}
                                    {{--                                        <i class="fa fa-fw fa-plus"></i>--}}
                                    {{--                                    </a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h4>Project Members</h4>
                    <table id="project-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Affiliations</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->team->members as $member)
                            <tr>
                                <td>
                                    <a href="{{route('projects.users.show', [$project, $member])}}">{{$member->name}}</a>
                                </td>
                                <td>{{$member->affiliations}}</td>
                                <td>
                                    <a href="{{route('projects.users.remove', [$project, $member])}}">
                                        <i class="fa fa-fw fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <br>
                    <hr>
                    <br>
                    <h4>Project Admins</h4>
                    <table id="project-admins" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Affiliations</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->team->admins as $admin)
                            <tr>
                                <td>
                                    <a href="{{route('projects.users.show', [$project, $admin])}}">{{$admin->name}}</a>
                                </td>
                                <td>{{$admin->affiliations}}</td>
                                <td>
                                    @if($project->owner_id === $admin->id)
                                        (Project Owner)
                                    @else
                                        <a href="{{route('projects.admins.remove', [$project, $admin])}}">
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

                $('#project-admins').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
