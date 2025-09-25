@php
    $attributesCount = 0;
@endphp
<div class="row ml-2">
    @foreach($activity->entityStates as $es)
        @if($es->pivot->direction == "out")
            @foreach($es->attributes as $attribute)
                @php
                    $attributesCount++;
                @endphp
                <livewire:attributes.editable-attribute-value :attribute="$attribute"
                                                              :activity="$activity"
                                                              :experiment="$experiment"
                                                              :user="$user"
                                                              :key="$attribute->id"/>
            @endforeach
        @endif
    @endforeach
    @if($attributesCount == 0)
        <span class="ml-1">No Attributes</span>
    @endif
</div>
