@extends('layouts.app')

@section('pageTitle', 'Labs')

@section('content')
    <table class="table">
        <thead class="bg-primary text-white">
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        </thead>
        <tbody>
        @foreach($labs as $lab)
            <tr>
                <th scope="row">{{$lab->name}}</th>
                <td>{{$lab->description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
