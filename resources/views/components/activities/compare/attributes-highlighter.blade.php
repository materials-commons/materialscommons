@props(['attributes', 'side', 'comparerState'])

<dl class="row ml-2">
    @foreach($attributes->sortBy('name') as $attribute)
        @php
            $isUnique = false;
            $isDifferent = false;

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

        <dt class="col-7 {{$highlightClass}}">{{$attribute->name}}:</dt>
        <dd class="col-4 {{$highlightClass}}">
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
        </dd>
    @endforeach
</dl>