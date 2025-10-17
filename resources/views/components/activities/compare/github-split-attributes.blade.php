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

<table class="github-diff-table">
    <tbody>
        @if($hasAnyAttributes)
            @foreach($allAttributeNames as $attributeName)
                @php
                    $isRemoved = $comparerState->activity1OnlyAttributes->contains($attributeName);
                    $isAdded = $comparerState->activity2OnlyAttributes->contains($attributeName);
                    $isChanged = $comparerState->differentValueAttributes->contains($attributeName);
                    $isSame = $comparerState->sameAttributes->has($attributeName);

                    // Get attribute values
                    $attr1 = $attributes1ByName->get($attributeName);
                    $attr2 = $attributes2ByName->get($attributeName);

                    $value1 = null;
                    $unit1 = '';
                    $value2 = null;
                    $unit2 = '';

                    if ($attr1) {
                        $value1 = $attr1->values[0]->val["value"] ?? null;
                        $unit1 = $attr1->values[0]->unit ?? "";
                    }

                    if ($attr2) {
                        $value2 = $attr2->values[0]->val["value"] ?? null;
                        $unit2 = $attr2->values[0]->unit ?? "";
                    }

                    // If changed, use the stored values
                    if ($isChanged) {
                        $changedValues = $comparerState->changedAttributeValues->get($attributeName);
                        $value1 = $changedValues['old']['value'];
                        $unit1 = $changedValues['old']['unit'];
                        $value2 = $changedValues['new']['value'];
                        $unit2 = $changedValues['new']['unit'];
                    }

                    // If same, use stored value
                    if ($isSame) {
                        $sameValue = $comparerState->sameAttributes->get($attributeName);
                        $value1 = $sameValue['value'];
                        $unit1 = $sameValue['unit'];
                        $value2 = $sameValue['value'];
                        $unit2 = $sameValue['unit'];
                    }
                @endphp

                <tr>
                    @if($isRemoved)
                        {{-- Only in activity 1 --}}
                        <td class="github-gutter removed">-</td>
                        <td class="github-line removed">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value">{{$formatValue($value1, $unit1)}}</span>
                        </td>
                        <td class="github-gutter empty"></td>
                        <td class="github-line empty"></td>
                    @elseif($isAdded)
                        {{-- Only in activity 2 --}}
                        <td class="github-gutter empty"></td>
                        <td class="github-line empty"></td>
                        <td class="github-gutter added">+</td>
                        <td class="github-line added">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value">{{$formatValue($value2, $unit2)}}</span>
                        </td>
                    @elseif($isChanged)
                        {{-- Changed value --}}
                        <td class="github-gutter removed">-</td>
                        <td class="github-line removed">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value value-changed">{{$formatValue($value1, $unit1)}}</span>
                        </td>
                        <td class="github-gutter added">+</td>
                        <td class="github-line added">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value value-changed">{{$formatValue($value2, $unit2)}}</span>
                        </td>
                    @else
                        {{-- Same value --}}
                        <td class="github-gutter unchanged">&nbsp;</td>
                        <td class="github-line unchanged">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value">{{$formatValue($value1, $unit1)}}</span>
                        </td>
                        <td class="github-gutter unchanged">&nbsp;</td>
                        <td class="github-line unchanged">
                            <span class="github-attr-name">{{$attributeName}}:</span>
                            <span class="github-attr-value">{{$formatValue($value2, $unit2)}}</span>
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="github-line unchanged text-center text-muted">
                    No Attributes
                </td>
            </tr>
        @endif
    </tbody>
</table>
