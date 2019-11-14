@extends('layouts.app')

@section('pageTitle', 'Users')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Add Users
        @endslot

        @slot('body')
            <div class="row">
                <div class="col-6">
                    <table id="all-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>
                                    <a href="{{route('projects.users.add', [$project->id, $user->id])}}"><i
                                                class="fa fa-fw fa-plus"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table id="project-users" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->users as $puser)
                            <tr>
                                <td>{{$puser->name}}</td>
                                <td>
                                    @if($project->owner->id === $puser->id)
                                        (Project Owner)
                                    @else
                                        <a href="{{route('projects.users.remove', [$project->id, $puser->id])}}"><i
                                                    class="fa fa-fw fa-trash"></i></a>
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