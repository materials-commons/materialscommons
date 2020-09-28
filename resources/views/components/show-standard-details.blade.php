<form>
    <div class="row">
        <div class="col mb-2">
            <div>
                <span class="fs-9 grey-5">Last Updated: {{$item->updated_at->diffForHumans()}}</span>
                <span class="ml-3 fs-9 grey-5">Owner: {{$item->owner->name}}</span>
                {{ $slot ?? '' }}
            </div>
        </div>
    </div>
    @if(!blank($item->description))
        <x-show-description :description="$item->description"/>
    @elseif (!blank($item->summary))
        <x-show-summary :summary="$item->summary"/>
    @endif
</form>