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
                {{$run->script->scriptFile->fullPath()}}
            </a>
                <div class="row border">
                    <x-display-file :file="$run->script->scriptFile"
                                    :display-route="route('projects.files.display', [$project, $run->script->scriptFile])"></x-display-file>
                </div>
            <br/>
                <h4>Log</h4>
                <div class="row border">
                    <pre class="ml-3 mt-2" style="white-space: pre-wrap">{{$log}}</pre>
            </div>
        </x-slot:body>
    </x-card>
@stop