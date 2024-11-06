@extends('layouts.app')

@section('pageTitle', "{$project->name} - Users")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Project Members
            @if($project->owner->id === auth()->id() || $project->team->admins->contains('id', auth()->id()) || auth()->user()->is_admin)
                <a class="action-link float-right"
                   href="{{route('projects.users.edit', [$project])}}">
                    <i class="fas fa-plus mr-2"></i>Add Users
                </a>
            @endif
        @endslot

        @slot('body')
            <h4>Project Members</h4>
            <table id="users" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Affiliations</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->team->members->merge($project->team->admins) as $member)
                    <tr>
                        <td>
                            <a href="{{route('projects.users.show', [$project, $member])}}">
                                {{$member->name}}
                            </a>
                        </td>
                        <td>{{$member->email}}</td>
                        <td>{{$member->affiliations}}</td>
                        <td>{{$member->description}}</td>
                        <td>
                            @if ($project->owner_id === $member->id)
                                Owner
                            @else
                                {{$project->team->members->contains('id', $member->id) ? 'Member' : 'Admin'}}
                            @endif
                        </td>
                        <td>
                            @if($project->owner_id != $member->id)
                                @if(auth()->id() == $project->owner_id || $project->team->admins->contains('id', auth()->id()))
                                    @if($project->team->members->contains('id', $member->id))
                                        <a href="{{route('projects.users.remove', [$project, $member])}}">
                                            <i class="fa fas fa-trash"></i></a>
                                        <a href="{{route('projects.users.change-to-admin', [$project, $member])}}"
                                           class="ml-4">
                                            <i class="fa fas fa-fw fa-edit"></i>Make Admin
                                        </a>
                                    @else
                                        <a href="{{route('projects.admins.remove', [$project, $member])}}">
                                            <i class="fa fas fa-trash"></i></a>
                                        <a href="{{route('projects.users.change-to-member', [$project, $member])}}"
                                           class="ml-4">
                                            <i class="fa fas fa-fw fa-edit"></i>Make Member
                                        </a>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent


    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#users').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
