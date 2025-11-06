@extends('layouts.app')

@section('pageTitle', "{$project->name} - Filter By User")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Filter by user</h3>
    <br/>
    <ul class="list-unstyled">
        @foreach($members as $member)
            <li>
                <a href="{{route('projects.folders.filter.show-for-user', [$project, $member])}}">
                    Filter by {{$member->name}}
                </a>
            </li>
        @endforeach
    </ul>
@stop
