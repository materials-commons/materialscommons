@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Dataset Upload Wizard
        @endslot

        @slot('body')
            <div class="col-10">
                <form class="mb-3">
                    <p>
                        Materials Commons stores all data in projects. You can choose to create a new project, or
                        use
                        an existing one.
                    </p>
                    <div>
                        <a href="{{route('public.publish.wizard.create_project')}}"
                           class="btn btn-primary me-3">
                            Create Project
                        </a>

                        <a class="btn btn-info" href="{{route('public.publish.wizard.select_project')}}">
                            Select Project
                        </a>
                    </div>
                </form>
            </div>
        @endslot
    @endcomponent
@endsection