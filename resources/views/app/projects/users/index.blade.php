@extends('layouts.app')

@section('pageTitle', 'Users')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Users
            @if($project->owner->id === auth()->id())
                <a class="action-link float-right"
                   href="{{route('projects.users.edit', [$project])}}">
                    <i class="fas fa-edit mr-2"></i>Modify Users
                </a>
            @endif
        @endslot

        @slot('body')
            <h4>Project Members</h4>
            <table id="users" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Affiliations</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->team->members as $member)
                    <tr>
                        <td>
                            <a href="{{route('projects.users.show', [$project, $member])}}">
                                {{$member->name}}
                            </a>
                        </td>
                        <td>{{$member->affiliations}}</td>
                        <td>{{$member->description}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <br>
            <hr>
            <br>
            <h4>Project Admins</h4>
            <table id="admins" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Affiliations</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->team->admins as $admin)
                    <tr>
                        <td>
                            <a href="{{route('projects.users.show', [$project, $admin])}}">
                                {{$admin->name}}
                            </a>
                        </td>
                        <td>{{$admin->affiliations}}</td>
                        <td>{{$admin->description}}</td>
                    </tr>
                @endforeach
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

                $('#admins').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
