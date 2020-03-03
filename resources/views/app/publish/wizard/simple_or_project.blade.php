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
            <div class="col-6">
                <form class="form-group">
                    <p>
                        You can choose to start uploading directly, or select/create a project
                        for your data.
                    </p>
                    <div>
                        <a href="{{route('public.publish.wizard.create_default_project')}}"
                           class="btn btn-primary mr-3">
                            Upload Directly
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