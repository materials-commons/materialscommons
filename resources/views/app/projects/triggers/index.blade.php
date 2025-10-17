@extends('layouts.app')

@section('pageTitle', 'Triggers')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    <x-card>
        <x-slot:header>
            Triggers
            <a class="float-end action-link mr-4"
               href="{{route('projects.triggers.create', [$project])}}">
                <i class="fas fa-fw fa-plus mr-2"></i>Add Trigger
            </a>
        </x-slot:header>

        <x-slot:body>
            <h4>Triggers</h4>
            <br>
            <table id="triggers" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>When</th>
                    <th>What</th>
                    <th>Script</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($triggers as $trigger)
                    <td>
                        <a href="{{route('projects.triggers.show', [$project, $trigger])}}">{{$trigger->name}}</a>
                    </td>
                    <td>{{$trigger->description}}</td>
                    <td>{{$trigger->when}}</td>
                    <td>{{$trigger->what}}</td>
                    <td>
                        <a href="{{route('projects.files.show', [$project, $trigger->script->scriptFile])}}">{{$trigger->script->scriptFile->fullPath()}}</a>
                    </td>
                    <td>
                        <div class="float-end">
                            <a href="{{route('projects.triggers.edit', [$project, $trigger])}}" class="action-link">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>

                            <a href="{{route('projects.triggers.delete', [$project, $trigger])}}" class="action-link">
                                <i class="fas fa-fw fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                @endforeach
                </tbody>
            </table>
        </x-slot:body>
    </x-card>

@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#triggers').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        })
    </script>
@endpush