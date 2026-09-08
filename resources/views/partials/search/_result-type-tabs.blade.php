<div class="mb-4">
    <div class="d-flex flex-wrap gap-2">
        @foreach($types as $key => $label)
            <button type="button"
                    wire:click="setType('{{ $key }}')"
                    class="btn btn-sm {{ $activeType === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>
