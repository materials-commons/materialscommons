<a href="{{ $result['url'] }}"
   class="list-group-item list-group-item-action py-3">
    <div class="d-flex justify-content-between gap-3">
        <div class="min-w-0">
            <div class="fw-semibold">
                {{ $result['title'] }}
            </div>

            <div class="text-muted {{ $compact ? 'small' : '' }}">
                {{ $result['subtitle'] }}
            </div>

            @if(!$compact && !blank($result['description']))
                <div class="small text-muted mt-1">
                    {{ $result['description'] }}
                </div>
            @endif
        </div>

        <div class="text-end flex-shrink-0">
            <span class="badge bg-light text-dark border">
                {{ $result['type'] }}
            </span>

            @if(!blank($result['project']))
                <div class="small text-muted mt-1">
                    {{ $result['project'] }}
                </div>
            @endif
        </div>
    </div>
</a>
