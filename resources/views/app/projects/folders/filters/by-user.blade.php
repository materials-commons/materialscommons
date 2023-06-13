@extends('layouts.app')

@section('pageTitle', "{$project->name} - Filter By User")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Filter by user
        </x-slot>
        <x-slot name="body">
            <ul class="list-unstyled">
                @foreach($members as $member)
                    <li>
                        <a href="{{route('projects.folders.filter.show-for-user', [$project, $member])}}">
                            Filter by {{$member->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-slot>
    </x-card>
@stop
