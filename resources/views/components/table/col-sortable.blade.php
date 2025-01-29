@props(['column', 'sortCol', 'sortAsc'])

<a class="btn tt-none" wire:click="sortBy('{{ $column }}')">
    <div>
        {{ $slot }}
        @if($sortCol == $column)
            @if($sortAsc)
                <i class="fa fa-fw fa-sort-amount-up"></i>
            @else
                <i class="fa fa-fw fa-sort-amount-down"></i>
            @endif
        @else
            <i class="fa fa-fw fa-sort"></i>
        @endif
    </div>
</a>