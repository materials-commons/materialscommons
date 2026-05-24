@props([
    'comparerState',
    'attributes1',
    'attributes2',
])

@php
    $attributes1ByName = collect($attributes1)->keyBy('name');
    $attributes2ByName = collect($attributes2)->keyBy('name');

    // Get all attribute names sorted
    $allAttributeNames = $attributes1ByName->keys()
        ->merge($attributes2ByName->keys())
        ->unique()
        ->sort();

    $hasAnyAttributes = $allAttributeNames->isNotEmpty();
@endphp

<div class="diff-attributes">
    @if($hasAnyAttributes)
        @foreach($allAttributeNames as $attributeName)
            @php
                $isRemoved = $comparerState->activity1OnlyAttributes->contains($attributeName);
                $isAdded = $comparerState->activity2OnlyAttributes->contains($attributeName);
                $isChanged = $comparerState->differentValueAttributes->contains($attributeName);
                $isSame = $comparerState->sameAttributes->has($attributeName);

                // Helper function to format value
                $formatValue = function($value, $unit = '') {
                    if (is_array($value)) {
                        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    }
                    if (blank($value)) {
                        return 'No value';
                    }
                    return $value . ($unit ? ' ' . $unit : '');
                };
            @endphp

            @if($isRemoved)
                @php
                    $attr = $attributes1ByName[$attributeName];
                    $value = $attr->values[0]->val["value"] ?? null;
                    $unit = $attr->values[0]->unit ?? "";
                @endphp
                <div class="diff-line removed">
                    <span class="diff-symbol">-</span>
                    <span class="diff-attr-name">{{$attributeName}}:</span>
                    <span class="diff-attr-value">{{$formatValue($value, $unit)}}</span>
                </div>
            @elseif($isAdded)
                @php
                    $attr = $attributes2ByName[$attributeName];
                    $value = $attr->values[0]->val["value"] ?? null;
                    $unit = $attr->values[0]->unit ?? "";
                @endphp
                <div class="diff-line added">
                    <span class="diff-symbol">+</span>
                    <span class="diff-attr-name">{{$attributeName}}:</span>
                    <span class="diff-attr-value">{{$formatValue($value, $unit)}}</span>
                </div>
            @elseif($isChanged)
                @php
                    $changedValues = $comparerState->changedAttributeValues->get($attributeName);
                    $oldValue = $changedValues['old']['value'];
                    $oldUnit = $changedValues['old']['unit'];
                    $newValue = $changedValues['new']['value'];
                    $newUnit = $changedValues['new']['unit'];
                @endphp
                <div class="diff-line removed">
                    <span class="diff-symbol">-</span>
                    <span class="diff-attr-name">{{$attributeName}}:</span>
                    <span class="diff-attr-value">{{$formatValue($oldValue, $oldUnit)}}</span>
                </div>
                <div class="diff-line added">
                    <span class="diff-symbol">+</span>
                    <span class="diff-attr-name">{{$attributeName}}:</span>
                    <span class="diff-attr-value">{{$formatValue($newValue, $newUnit)}}</span>
                </div>
            @elseif($isSame)
                @php
                    $sameValue = $comparerState->sameAttributes->get($attributeName);
                    $value = $sameValue['value'];
                    $unit = $sameValue['unit'];
                @endphp
                <div class="diff-line unchanged">
                    <span class="diff-symbol">&nbsp;</span>
                    <span class="diff-attr-name">{{$attributeName}}:</span>
                    <span class="diff-attr-value">{{$formatValue($value, $unit)}}</span>
                </div>
            @endif
        @endforeach
    @else
        <div class="diff-line unchanged">
            <span class="diff-symbol">&nbsp;</span>
            <span class="text-muted">No Attributes</span>
        </div>
    @endif
</div>
