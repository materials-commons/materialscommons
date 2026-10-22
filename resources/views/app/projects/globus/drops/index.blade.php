@extends('layouts.app')

@section('pageTitle', "{$project->name} - Drops")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    <h3 class="text-center">Drops</h3>

    <div>
        <a class="action-link float-end" href="{{route('projects.globus.drops.create', [$project])}}">
            <i class="fas fa-plus me-2"></i> New Globus Drop
        </a>
    </div>
    <br/>
    <br/>

    <table id="drops" class="table table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>URL</th>
            <th>Users</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($drops as $drop)
            <tr>
                <td>{{ $drop->name }}</td>
                <td>
                    <a href="{{ $drop->globus_url }}" target="_blank" rel="noopener noreferrer">
                        {{ $drop->globus_url }}
                    </a>
                </td>
                <td>
                    @if(!empty($drop->globus_accounts))
                        {{ implode(', ', $drop->globus_accounts) }}
                    @else
                        <span class="text-muted">No users</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('projects.globus.drops.import', [$project, $drop]) }}"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-fw fa-file-import"></i> import
                    </a>

                    <a href="{{ route('projects.globus.drops.delete', [$project, $drop]) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this drop site?');">
                        <i class="fas fa-fw fa-trash-alt"></i> delete
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#drops').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
