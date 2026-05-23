@props([
    'logEntries' => collect(),
])

<div class="rounded-3 p-3"
     style="background:#101828;color:#d0d5dd;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,monospace;font-size:.82rem;max-height:460px;overflow:auto;">
    @forelse($logEntries as $entry)
        @php
            $levelColor = match ($entry->level?->value) {
                'success' => '#86efac',
                'warning' => '#fde68a',
                'error' => '#fca5a5',
                'debug' => '#c4b5fd',
                default => '#84caff',
            };
        @endphp

        <div class="mb-1" style="white-space:pre-wrap;">
            <span style="color:#98a2b3;">{{ $entry->created_at?->format('H:i:s') }}</span>
            <span style="color:{{ $levelColor }};">{{ $entry->level?->label() ?? 'INFO' }}</span>
            <span>{{ $entry->message }}</span>
        </div>
    @empty
        <div style="color:#98a2b3;">No log entries recorded yet.</div>
    @endforelse
</div>
