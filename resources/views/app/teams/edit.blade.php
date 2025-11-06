@extends('layouts.app')

@section('pageTitle', 'Create Team')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Create Team</h3>
    <br/>

    <form method="post" action="{{route('teams.store')}}" id="team-create">
        @csrf
        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                   placeholder="Name...">
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
                      placeholder="Description...">{{old('description')}}</textarea>
        </div>
        <div class="float-end">

        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#all-users').DataTable({
                    stateSave: true,
                });

                $('#project-users').DataTable({
                    stateSave: true,
                });

                $('#team-projects').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
