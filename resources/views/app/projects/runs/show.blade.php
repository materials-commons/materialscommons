@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Run")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    <x-card>
        <x-slot:header>Run Status</x-slot:header>
        <x-slot:body>
            @if(!is_null($run->finished_at))
                <span>Status: Completed</span>
            @elseif(!is_null($run->failed_at))
                <span>Status: Errored</span>
            @else
                <span>Status: Not Run Yet</span>
            @endif
            <br>
            <a href="{{route('projects.files.show', [$project, $run->script->scriptFile])}}">
                @if($run->script->scriptFile->directory->name == "/")
                    /{{$run->script->scriptFile->name}}
                @else
                    {{$run->script->scriptFile->directory->path}}/{{$run->script->scriptFile->name}}
                @endif
            </a>
            <br/>
            <br/>
            <h5>Log</h5>
            <div class="col-8">
                <pre style="white-space: pre-wrap">{{$log}}</pre>
            </div>
        </x-slot:body>
    </x-card>
@stop