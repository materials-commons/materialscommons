@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Data Communities
        @endslot

        @slot('body')
            <table id="communities" class="table table-hover">
                <thead>
                <tr>
                    <th>Community</th>
                    <th>Organizer</th>
                    <th>Description</th>
                    <th>Datasets</th>
                </tr>
                </thead>
                <tbody>
                @foreach($communities as $community)
                    <tr>
                        <td>
                            <a href="#">{{$community->name}}</a>
                        </td>
                        <td>{{$community->owner->name}}</td>
                        <td>{{$community->description}}</td>
                        <td>{{$community->datasets_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#communities').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
