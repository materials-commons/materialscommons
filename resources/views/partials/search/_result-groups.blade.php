<div class="search-result-groups">
    @foreach($groups as $group)
        <section class="{{ $compact ? 'mb-3' : 'mb-4' }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">
                    <i class="fas {{ $group['icon'] }} me-2 text-muted"></i>
                    {{ $group['label'] }}
                    <span class="badge bg-light text-dark border ms-1">{{ $group['count'] }}</span>
                </h5>

                @if($showViewTypeLinks && $group['key'] !== 'all')
                    <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            wire:click="setType('{{ $group['key'] }}')">
                        View only {{ strtolower($group['label']) }}
                    </button>
                @endif
            </div>

            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    @foreach($group['results'] as $result)
                        @include('partials.search._result-card', [
                            'result' => $result,
                            'compact' => $compact,
                        ])
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
</div>
