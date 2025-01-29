@extends('layouts.app')

@section('pageTitle', 'Runs')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    <x-card>
        <x-slot:header>Runs</x-slot:header>
        <x-slot:body>
            <table id="runs" class="table table-hover" style="width: 100%">
                <thead>
                <tr>
                    <th>Script</th>
                    <th>When</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($scriptRuns as $run)
                    <tr>
                        <td>
                            <a href="{{route('projects.runs.show', [$project, $run])}}">
                                {{$run->script->scriptFile->fullPath()}}
                            </a>
                        </td>
                        <td>
                            <span>
                                @if(!is_null($run->started_at))
                                    {{$run->started_at->diffForHumans()}}
                                @else
                                    Hasn't Run
                                @endif
                            </span>
                        </td>
                        <td>
                            @if(is_null($run->started_at))
                                Waiting To Run
                            @elseif(!is_null($run->finished_at))
                                Successful
                            @elseif(is_null($run->failed_at))
                                Running
                            @else
                                Errored
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-slot:body>
    </x-card>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#runs').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop