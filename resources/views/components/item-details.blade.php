<form>
    <div class="row">
        <div class="col mb-2">
            <div class="">
                {{$slotStart ?? ''}}
                <span class="fs-9 grey-5">Last Updated {{$item->updated_at->diffForHumans()}}</span>
                {{$slot}}
                <span class="ml-3 fs-9 grey-5">Owner: {{$item->owner->name}}</span>
                {{$slotEnd ?? ''}}
            </div>
        </div>
    </div>

    <div class="align-items-center">
        {{$top ?? ''}}
    </div>

    @unless(isset($noDescription))
        @isset($item->summary)
            @if(blank($item->description))
                <x-show-summary :summary="$item->summary"></x-show-summary>
            @endif
        @endisset
        <x-show-description :description="$item->description"></x-show-description>
    @endunless

    {{$bottom ?? ''}}

    <hr>
    <br>

</form>