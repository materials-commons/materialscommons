<form>
    <div class="row">
        <div class="col mb-2">
            <div>
                @if(isset($item->loading_finished_at) && !blank($item->loading_finished_at))
                    <span class="fs-10 grey-5">Last Loaded At: {{$item->loading_finished_at->diffForHumans()}}</span>
                @else
                    <span class="fs-10 grey-5">Last Updated: {{$item->updated_at->diffForHumans()}}</span>
                @endif
                <span class="ml-3 fs-10 grey-5">Owner: {{$item->owner->name}}</span>
                {{ $slot ?? '' }}
                @if (isset($item->id))
                    <span class="ml-3 fs-10 grey-5">ID: {{$item->id}}</span>
                @endif
            </div>
        </div>
    </div>
    @if(isset($item->description) && !blank($item->description))
        <x-show-description :description="$item->description"/>
    @elseif (isset($item->summary) && !blank($item->summary))
        <x-show-summary :summary="$item->summary"/>
    @endif
</form>
