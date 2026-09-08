@props([
    'metrics' => [],
])

<div class="row g-2 mb-3">
    @foreach($metrics as $metric)
        <div class="col-6">
            <div class="border rounded-3 p-2 bg-white">
                <div class="fw-bold {{ $metric['color'] ?? 'text-secondary' }}" style="font-size:1.15rem;">
                    {{ $metric['value'] }}
                </div>
                <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                    {{ $metric['label'] }}
                </div>
            </div>
        </div>
    @endforeach
</div>
