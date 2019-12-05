@extends('layouts.app')

@section('pageTitle', 'Show Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Show Community {{$community->name}}
            <a class="action-link float-right" href="{{route('communities.edit', [$community])}}">
                <i class="fas fa-edit mr-2"></i>Edit Community
            </a>
        @endslot

        @slot('body')
            <form>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{$community->name}}" type="text"
                           placeholder="Name..." readonly>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." readonly>{{$community->description}}</textarea>
                </div>
            </form>

            <br>
            <h3>Datasets in Community</h3>
            <br>
            <table id="datasets" class="table table-hover">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Description</th>
                    <th>Owner</th>
                    <th>Updated</th>
                </tr>
                </thead>
                <tbody>
                @foreach($community->datasets as $dataset)
                    <tr>
                        <td>{{$dataset->name}}</td>
                        <td>{{$dataset->description}}</td>
                        <td>{{$dataset->owner->name}}</td>
                        <td>{{$dataset->updated_at->diffForHumans()}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br>
            <div class="float-right">
                <a class="btn btn-success" href="{{route('communities.index')}}">Done</a>
            </div>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            mcutil.setupDatatableOnDocumentReady('#datasets');
        </script>
    @endpush
@stop