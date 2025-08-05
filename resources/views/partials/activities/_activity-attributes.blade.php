@php
    $attributesCount = 0;
//    $isEditing = true;
@endphp
<div class="row ml-2">
    @foreach($activity->attributes as $attribute)
        @php
            $attributesCount++;
        @endphp
        <livewire:attributes.editable-attribute-value :attribute="$attribute"
                                                      :activity="$activity"
                                                      :experiment="$experiment"
                                                      :user="$user"
                                                      :key="$attribute->id"/>
    @endforeach
    @if($attributesCount == 0)
        <span class="ml-1">No Attributes</span>
    @endif
</div>
