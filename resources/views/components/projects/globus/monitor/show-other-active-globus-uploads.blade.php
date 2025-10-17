<div>
    <span>There are {{$otherActive->count()}} other active globus transfers</span>
    @if($otherActive->count() != 0)
        <ul class="list-unstyled">
            @foreach($otherActive as $activeTransfer)
                <li class="ms-4">
                    {{$activeTransfer->owner->name}} started transferring
                    files {{$activeTransfer->created_at->diffForHumans()}}
                    ({{$activeTransfer->globus_request_files_count}} @choice('file|files', $activeTransfer->globus_request_files_count)
                    uploaded)
                </li>
            @endforeach
        </ul>
    @endif
</div>