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
            <table id="users" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
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
            });
        </script>
    @endpush
@stop
