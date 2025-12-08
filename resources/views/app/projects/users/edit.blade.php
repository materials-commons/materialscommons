@extends('layouts.app')

@section('pageTitle', "{$project->name} - Modify members")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Modify Members of Project {{$project->name}}</h3>
    <br/>

    <a href="{{route('projects.users.index', [$project])}}" class="btn btn-success">Done</a>
    <br>
    <br>
    <h4>Select Users To Add</h4>
    <table id="all-users" class="table table-hover">
        <thead>
        <tr>
            <th>Full Name</th>
            <th>Last Name</th>
            <th>Affiliations</th>
            <th>Email</th>
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
                <td>{{Illuminate\Support\Str::afterLast($user->name, " ")}}</td>
                <td>{{$user->affiliations}}</td>
                <td>{{$user->email}}</td>
                <td>
                    <div class="dropdown">
                        <a class="nav-link fs-11 ms-5 dropdown-toggle td-none" href="#" role="button"
                           id="dropdownMenuLink"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-fw fas fa-plus me-2"></i>
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
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#all-users').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
