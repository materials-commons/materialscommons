@extends('layouts.app')

@section('pageTitle', "Drop Link No Longer Available")

@section('content')
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
            </div>

            <h2 class="mb-3">This drop link is no longer available</h2>

            <p class="lead mb-3">
                The drop may have already been completed, removed, or is no longer accepting uploads.
            </p>

            <p class="text-muted mb-4">
                If you still need to transfer files, please contact the person who shared this drop with you
                and ask them to send a new link.
            </p>

            <a href="/" class="btn btn-primary">
                Return to Materials Commons
            </a>
        </div>
    </div>
@stop
