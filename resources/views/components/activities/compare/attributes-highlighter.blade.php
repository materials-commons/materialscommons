@props(['attrs', 'side', 'comparerState'])

<div class="row ms-2">
    @php
        $attributesCount = 0;
    @endphp
    @foreach($attrs->sortBy('name') as $attribute)
        @php
            $isUnique = false;
            $isDifferent = false;
            $attributesCount++;

            if ($side === 'left' && $comparerState->activity1OnlyAttributes->contains($attribute->name)) {
                $isUnique = true;
            }

            if ($side === 'right' && $comparerState->activity2OnlyAttributes->contains($attribute->name)) {
                $isUnique = true;
            }

            if ($side === 'left' && $comparerState->differentValueAttributes->contains($attribute->name)) {
                $isDifferent = true;
            }

            if ($side === 'right' && $comparerState->differentValueAttributes->contains($attribute->name)) {
                $isDifferent = true;
            }

            $highlightClass = '';
            if ($isUnique) {
                $highlightClass = 'bg-primary text-white';
            } elseif ($isDifferent) {
                $highlightClass = 'bg-warning';
            }
        @endphp

        @continue($isUnique && $comparerState->hideUniqueOnLeft && $side === 'left')
        @continue($isUnique && $comparerState->hideUniqueOnRight && $side === 'right')
        @continue($isDifferent && $comparerState->hideDifferent)
        @continue((!$isUnique && !$isDifferent) && $comparerState->hideSame)

        <div class="attribute-row row col-11 ms-1 {{$highlightClass}}">
            <div class="col-7">{{$attribute->name}}:</div>
            <div class="col-4">
                @if(is_array($attribute->values[0]->val["value"]))
                    @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                @else
                    @if(blank($attribute->values[0]->val["value"]))
                        No value
                    @else
                        {{$attribute->values[0]->val["value"]}}
                    @endif
                @endif
                @if($attribute->values[0]->unit != "")
                    {{$attribute->values[0]->unit}}
                @endif
            </div>
        </div>
    @endforeach
    @if($attributesCount == 0)
        <span class="ms-1">No Attributes</span>
    @endif
</div>
