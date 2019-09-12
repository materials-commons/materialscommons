@component('components.card')
    @slot('header')
        File: {{$file->name}}
        <a class="float-right action-link" href="#">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>

        <a class="float-right action-link mr-4" href="#">
            <i class="fas fa-trash-alt mr-2"></i>Delete
        </a>
    @endslot

    @slot('body')
        <div class="ml-5">
            <dl class="row">
                <dt class="col-sm-2">Name</dt>
                <dd class="col-sm-10">{{$file->name}}</dd>
                <dt class="col-sm-2">Owner</dt>
                <dd class="col-sm-10">{{$file->owner->name}}</dd>
                <dt class="col-sm-2">Last Updated</dt>
                <dd class="col-sm-10">{{$file->updated_at->diffForHumans()}}</dd>
            </dl>
        </div>
        <div class="row ml-5">
            <h5>Description</h5>
        </div>
        <div class="row ml-5">
            <p>{{$file->description}}</p>
        </div>
    @endslot
@endcomponent