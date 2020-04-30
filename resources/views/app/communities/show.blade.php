@extends('layouts.app')

@section('pageTitle', 'Show Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @include('partials.communities._show', [
        'editCommunityRoute' => route('communities.edit', [$community]),
        'doneRoute' => route('communities.index'),
    ])
@stop

{{--@section('content')--}}
{{--    @component('components.card')--}}
{{--        @slot('header')--}}
{{--            Community: {{$community->name}}--}}
{{--            <a class="action-link float-right" href="{{route('communities.edit', [$community])}}">--}}
{{--                <i class="fas fa-edit mr-2"></i>Edit Community--}}
{{--            </a>--}}
{{--        @endslot--}}

{{--        @slot('body')--}}
{{--            <form>--}}
{{--                <div class="form-group">--}}
{{--                    <label for="name">Name</label>--}}
{{--                    <input class="form-control" id="name" name="name" value="{{$community->name}}" type="text"--}}
{{--                           placeholder="Name..." readonly>--}}
{{--                </div>--}}

{{--                <div class="form-group">--}}
{{--                    <label for="name">Summary</label>--}}
{{--                    <input class="form-control" id="summary" name="summary" value="{{$community->summary}}" type="text"--}}
{{--                           placeholder="Summary..." readonly>--}}
{{--                </div>--}}

{{--                <div class="form-group">--}}
{{--                    <label for="description">Description</label>--}}
{{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
{{--                              placeholder="Description..." readonly>{{$community->description}}</textarea>--}}
{{--                </div>--}}
{{--            </form>--}}

{{--            <br>--}}
{{--            @include('app.communities.show-tabs.tabs')--}}
{{--            <br>--}}

{{--            @if(Request::routeIs('public.communities.show'))--}}
{{--                @include('app.communities.show-tabs.datasets')--}}
{{--            @elseif (Request::routeIs('public.communities.practices.show'))--}}
{{--                @include('app.communities.show-tabs.practices')--}}
{{--            @endif--}}

{{--            --}}{{-- @if routes here --}}
{{--            --}}{{--            <h3>Datasets in Community</h3>--}}
{{--            --}}{{--            <br>--}}
{{--            --}}{{--            <table id="datasets" class="table table-hover">--}}
{{--            --}}{{--                <thead>--}}
{{--            --}}{{--                <tr>--}}
{{--            --}}{{--                    <th>Dataset</th>--}}
{{--            --}}{{--                    <th>Description</th>--}}
{{--            --}}{{--                    <th>Owner</th>--}}
{{--            --}}{{--                    <th>Updated</th>--}}
{{--            --}}{{--                </tr>--}}
{{--            --}}{{--                </thead>--}}
{{--            --}}{{--                <tbody>--}}
{{--            --}}{{--                @foreach($community->datasets as $dataset)--}}
{{--            --}}{{--                    <tr>--}}
{{--            --}}{{--                        <td>{{$dataset->name}}</td>--}}
{{--            --}}{{--                        <td>{{$dataset->description}}</td>--}}
{{--            --}}{{--                        <td>{{$dataset->owner->name}}</td>--}}
{{--            --}}{{--                        <td>{{$dataset->updated_at->diffForHumans()}}</td>--}}
{{--            --}}{{--                    </tr>--}}
{{--            --}}{{--                @endforeach--}}
{{--            --}}{{--                </tbody>--}}
{{--            --}}{{--            </table>--}}
{{--            <br>--}}
{{--            <div class="float-right">--}}
{{--                <a class="btn btn-success" href="{{route('communities.index')}}">Done</a>--}}
{{--            </div>--}}
{{--        @endslot--}}
{{--    @endcomponent--}}

{{--@stop--}}