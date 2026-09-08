@props([
    'validationMessages' => collect(),
])

@if($validationMessages->isEmpty())
    <div class="text-muted text-center py-4">
        No validation messages recorded yet.
    </div>
@else
    <div class="list-group">
        @foreach($validationMessages as $message)
            <div class="list-group-item">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold">{{ $message->title }}</div>

                        @if(!blank($message->message))
                            <div class="text-muted small mt-1">{{ $message->message }}</div>
                        @endif

                        <div class="d-flex flex-wrap gap-2 mt-2 small text-muted">
                            @if(!blank($message->worksheet_name))
                                <span>
                                    <i class="fas fa-table me-1"></i>
                                    {{ $message->worksheet_name }}
                                </span>
                            @endif

                            @if(!blank($message->cell_coordinate))
                                <span>
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $message->cell_coordinate }}
                                </span>
                            @elseif(!blank($message->row_number))
                                <span>
                                    <i class="fas fa-list-ol me-1"></i>
                                    Row {{ $message->row_number }}
                                </span>
                            @endif

                            @if(!blank($message->column_name))
                                <span>
                                    <i class="fas fa-columns me-1"></i>
                                    {{ $message->column_name }}
                                </span>
                            @endif

                            @if(!blank($message->code))
                                <span>
                                    <code>{{ $message->code }}</code>
                                </span>
                            @endif
                        </div>
                    </div>

                    <span class="badge {{ $message->severity?->badgeClass() ?? 'text-bg-secondary' }}">
                        {{ $message->severity?->label() ?? 'Info' }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endif
