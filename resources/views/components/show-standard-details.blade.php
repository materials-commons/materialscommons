<form>
    <div class="row">
        <div class="col mb-2">
            <div>
                <span class="fs-10 grey-5">Last Updated: {{$item->updated_at->diffForHumans()}}</span>
                <span class="ml-3 fs-10 grey-5">Owner: {{$item->owner->name}}</span>
                {{ $slot ?? '' }}
                <span class="ml-3 fs-10 grey-5">ID: {{$item->id}}</span>
            </div>
        </div>
    </div>
    @if(!blank($item->description))
        <x-show-description :description="$item->description"/>
    @elseif (!blank($item->summary))
        <x-show-summary :summary="$item->summary"/>
    @endif
</form>
