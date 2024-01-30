<form>
    <div class="row">
        <div class="col mb-2">
            <div>
                <span class="fs-10 grey-5">ID: {{$tr->id}}</span>
                <span class="fs-10 grey-5 ml-3">Started: {{$tr->created_at->diffForHumans()}}</span>
                <span class="fs-10 grey-5 ml-3">Files being uploaded: {{number_format($tr->transfer_request_files_count)}}</span>
                <span class="fs-10 grey-5 ml-3">Project: <a
                            href="{{route('projects.show', [$tr->project])}}">{{$tr->project->name}}</a></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <span class="fs-10 grey-5">User: {{$tr->owner->name}}</span>
            <span class="fs-10 grey-5 ml-3">Email: {{$tr->owner->email}}</span>
            <span class="fs-10 grey-5 ml-3">ID: {{$tr->owner->id}}</span>
        </div>
    </div>
</form>