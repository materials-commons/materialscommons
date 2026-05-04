<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-compass me-1"></i>Research Summary
            </h6>

            @if($hasSummary)
                <button type="button"
                        class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#research-summary-modal">
                    <i class="fas fa-edit me-1"></i>{{ $buttonLabel }}
                </button>
            @endif
        </div>

        @if($hasSummary)
            <div class="text-muted" style="font-size:.9rem; white-space:pre-line;">
                {{ $user->research_summary }}
            </div>
        @else
            <div class="border rounded bg-light p-3">
                <p class="text-muted mb-3" style="font-size:.9rem;">
                    Add a short private summary of your research interests, current focus areas,
                    datasets, and publication goals.
                </p>

                <button type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#research-summary-modal">
                    <i class="fas fa-plus me-1"></i>{{ $buttonLabel }}
                </button>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="research-summary-modal" tabindex="-1" aria-labelledby="research-summary-modal-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST"
              action="{{ route('dashboard.my-research.research-summary.update') }}"
              class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header py-2">
                <h6 class="modal-title fw-semibold" id="research-summary-modal-label">
                    {{ $modalTitle }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3">
                <label for="research-summary" class="form-label fw-semibold">
                    Research Summary
                </label>

                <textarea id="research-summary"
                          name="research_summary"
                          class="form-control @error('research_summary') is-invalid @enderror"
                          rows="10"
                          maxlength="10000"
                          placeholder="Describe your research interests, current projects, datasets, publication goals, or areas of focus.">{{ old('research_summary', $user->research_summary) }}</textarea>

                @error('research_summary')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror

                <div class="form-text">
                    This summary is private and shown on your My Research dashboard.
                </div>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-save me-1"></i>Save Summary
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->has('research_summary'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('research-summary-modal');

                if (modal) {
                    Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endpush
@endif
