@extends('layouts.email')

@section('content')
    <p>
        Dear {{$user->name}},
    </p>
    <p>
        You registered for updates on the dataset titled {{$dataset->name}}.
    </p>
    <p>
        That dataset has been updated. To view the updated dataset please click <a href="{{route('published.datasets.overview.show', [$dataset])}}">here</a>.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection