@props([
    'processResults' => collect(),
])

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>Worksheet</th>
            <th>Process Step</th>
            <th>Category</th>
            <th class="text-end">Samples</th>
            <th class="text-end">Activities</th>
            <th class="text-end">Attributes</th>
            <th class="text-end">Files</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        @forelse($processResults as $result)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $result->worksheet_name }}</div>
                    @if(!blank($result->message))
                        <div class="text-muted small">{{ $result->message }}</div>
                    @endif
                </td>
                <td>{{ $result->process_name ?? $result->worksheet_name }}</td>
                <td>
                    @if(!blank($result->category))
                        <span class="badge bg-light text-muted border">{{ $result->category }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-end">{{ $result->sample_count }}</td>
                <td class="text-end">{{ $result->activity_count }}</td>
                <td class="text-end">{{ $result->attribute_count }}</td>
                <td class="text-end">{{ $result->file_count }}</td>
                <td>
                    <span class="badge {{ $result->status?->badgeClass() ?? 'text-bg-secondary' }}">
                        {{ $result->status?->label() ?? 'Pending' }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-muted text-center py-4">
                    No process results recorded yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
