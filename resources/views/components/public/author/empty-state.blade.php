@props([
    'icon' => 'fas fa-info-circle',
    'title' => 'Nothing to show',
    'message' => null,
])

<div class="border rounded bg-light p-4 text-center">
    <div class="mb-2 text-muted">
        <i class="{{ $icon }} fa-2x"></i>
    </div>

    <h6 class="fw-semibold mb-1">{{ $title }}</h6>

    @if(filled($message))
        <p class="text-muted mb-0" style="font-size:.9rem;">
            {{ $message }}
        </p>
    @endif
</div>
