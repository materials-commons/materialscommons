@extends('layouts.app')

@section('pageTitle', "Transfer Marked Done")

@section('content')
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
            </div>

            <h2 class="mb-3">Transfer marked done</h2>

            <p class="lead mb-3">
                Thank you. The transfer for <strong>{{$drop->name}}</strong> has been marked as done.
            </p>

            <p class="text-muted mb-4">
                {{$drop->owner->name}} has been notified that the drop is ready to import.
                The drop is no longer available for uploads.
            </p>

            <a href="/" class="btn btn-primary">
                Return to Materials Commons
            </a>
        </div>
    </div>
@stop
