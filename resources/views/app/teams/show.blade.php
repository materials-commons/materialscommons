@extends('layouts.app')

@section('pageTitle', 'Show Team')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Team: {{$team->name}}
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $team])
            @endcomponent

            <h4>Projects In Team</h4>
            <br>
            <table id="team-projects" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Description</th>
                    <th>Last Updated</th>
                </tr>
                </thead>
                <tbody>
                @foreach($team->projects as $project)
                    <tr>
                        <td>
                            <a href="{{route('projects.show', [$project])}}">{{$project->name}}</a>
                        </td>
                        <td>{{$project->summary}}</td>
                        <td>{{$project->updated_at->diffForHumans()}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br>
            <hr>
            <br>
            <h4>Project Administators</h4>
            <br>
            <table id="team-admins" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Affiliations</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                @foreach($team->admins as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->affiliations}}</td>
                        <td>{{$user->description}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br>
            <hr>
            <br>
            <h4>Project Members</h4>
            <br>
            <table id="team-members" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Affiliations</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                @foreach($team->members as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->affiliations}}</td>
                        <td>{{$user->description}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#team-projects').DataTable({
                stateSave: true,
            });

            $('#team-admins').DataTable({
                stateSave: true,
            });

            $('#team-members').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush