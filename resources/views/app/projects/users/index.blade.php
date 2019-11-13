@extends('layouts.app')

@section('pageTitle', 'Users')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Users
            <a class="action-link float-right"
               href="{{route('projects.users.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i>Add Users
            </a>
        @endslot

        @slot('body')
            <table id="users" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    @if($project->owner->id === auth()->id())
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @if($project->owner->id === auth()->id())
                    @foreach($project->users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            @if($user->id === auth()->id())
                                <td></td>
                            @else
                                <td>
                                    <a data-toggle="modal" href="#user-delete-{{$user->id}}" class="action-link">
                                        <i class="fas fa-fw fa-trash-alt mr-1"></i>Remove User
                                    </a>
                                    @component('app.projects.users.delete-project-user', ['project' => $project, 'user' => $user])
                                    @endcomponent
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    @foreach($project->users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        @endslot
    @endcomponent


    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#users').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
