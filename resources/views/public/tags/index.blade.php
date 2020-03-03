@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Published Data Tags
        @endslot

        @slot('body')
            <table id="tags" class="table table-hover">
                <thead>
                <tr>
                    <th>Tag</th>
                    <th># Datasets</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tags as $tag => $count)
                    <tr>
                        <td>
                            <a class="badge badge-success fs-11"
                               href="{{route('public.tags.search', ['tag' => $tag])}}">
                                {{$tag}}
                            </a>
                        </td>
                        <td>{{$count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#tags').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
