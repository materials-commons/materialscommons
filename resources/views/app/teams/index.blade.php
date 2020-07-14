@extends('layouts.app')

@section('pageTitle', 'Teams')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Teams
        @endslot

        @slot('body')
            <h4>Teams I Administor</h4>
            <br>

            <table id="admin-teams" class="table table-hover" style="width: 100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Last Updated</th>
                    <th># Members</th>
                    <th># Projects</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->adminTeams as $team)
                    <tr>
                        <td>
                            <a href="{{route('teams.show', [$team])}}">{{$team->name}}</a>
                        </td>
                        <td>{{$team->updated_at->diffForHumans()}}</td>
                        <td>{{$team->members->count() + $team->admins->count()}}</td>
                        <td>{{$team->projects->count()}}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <br>
            <hr>
            <br>
            <h4>Teams I'm A Member Of</h4>
            <br>

            <table id="member-teams" class="table table-hover" style="width: 100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Last Updated</th>
                    <th># Members</th>
                    <th># Projects</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->memberOfTeams as $team)
                    <tr>
                        <td>
                            <a href="{{route('teams.show', [$team])}}">{{$team->name}}</a>
                        </td>
                        <td>{{$team->updated_at->diffForHumans()}}</td>
                        <td>{{$team->members->count() + $team->admins->count()}}</td>
                        <td>{{$team->projects->count()}}</td>
                        <td></td>
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
            $('#admin-teams').DataTable({
                stateSave: true,
            });

            $('#member-teams').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
