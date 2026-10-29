@extends('layouts.app')

@section('pageTitle', "Confirm Transfer Complete")

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Confirm that your transfer is complete</h4>
                <p>
                    Submitting this form will mark the transfer as done and notify {{$drop->owner->name}}
                    that the drop is ready to import.
                </p>
                <hr>
                <p class="mb-0">
                    After submitting, the drop site will no longer be available for uploading files.
                    You can optionally include a message for {{$drop->owner->name}} below.
                </p>
            </div>
            <form action="{{route('globus-drop.mark-done', [$drop->uuid])}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Optional Message</label>
                    <textarea class="form-control" name="message" id="message" rows="6"
                              placeholder="Optional: add any notes or a message to {{$drop->owner->name}}"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="/" class="btn btn-secondary me-2">
                        Cancel - Do Not Mark Done
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Mark Transfer Done
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
