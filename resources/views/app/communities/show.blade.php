@extends('layouts.app')

@section('pageTitle', 'Show Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @include('partials.communities._show', [
        'editCommunityRoute' => route('communities.edit', [$community]),
        'doneRoute' => route('communities.index'),
        'showRouteName' => 'communities.show',
        'practicesRouteName' => 'communities.practices.show',
    ])
@stop


{{--                        <h3>Datasets in Community</h3>--}}
{{--                        <br>--}}
{{--                        <table id="datasets" class="table table-hover">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>Dataset</th>--}}
{{--                                <th>Description</th>--}}
{{--                                <th>Owner</th>--}}
{{--                                <th>Updated</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($community->datasets as $dataset)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$dataset->name}}</td>--}}
{{--                                    <td>{{$dataset->description}}</td>--}}
{{--                                    <td>{{$dataset->owner->name}}</td>--}}
{{--                                    <td>{{$dataset->updated_at->diffForHumans()}}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
