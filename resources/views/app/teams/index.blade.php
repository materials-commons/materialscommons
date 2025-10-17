@extends('layouts.app')

@section('pageTitle', 'Teams')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Teams
            <a class="action-link float-end"
               href="{{route('teams.create')}}">
                <i class="fas fa-plus mr-2"></i>Create Team
            </a>
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
                        <td>
                            <div class="float-end">
                                <a href="{{route('teams.show', [$team])}}" class="action-link">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="{{route('teams.modify-users-projects', [$team])}}" class="action-link">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                {{--                                @if(auth()->id() == $proj->owner_id)--}}
                                {{--                                    <a data-toggle="modal" href="#project-delete-{{$proj->id}}" class="action-link">--}}
                                {{--                                        <i class="fas fa-fw fa-trash-alt"></i>--}}
                                {{--                                    </a>--}}
                                {{--                                @endif--}}
                            </div>
                        </td>
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
