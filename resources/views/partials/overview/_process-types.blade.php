@if(isset($activitiesGroup) && sizeof($activitiesGroup) != 0)
    <div class="mb-3">
        <label for="process-types" class="fw-semibold text-muted small text-uppercase" style="letter-spacing:.03em;">
            <i class="fas fa-cogs me-1"></i>Process Types
            <span class="badge text-bg-secondary ms-1" style="font-size:.7rem;">{{ sizeof($activitiesGroup) }}</span>
        </label>
        <div class="d-flex flex-wrap gap-1 mt-1">
            @foreach($activitiesGroup as $ag)
                <span class="badge text-bg-light border text-dark" style="font-size:.78rem; font-weight:normal;">
                    {{ $ag->name }}
                    <span class="badge text-bg-secondary ms-1" style="font-size:.7rem;">{{ $ag->count }}</span>
                </span>
            @endforeach
        </div>
    </div>
@endif
