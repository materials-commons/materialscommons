@props([])

{{-- Inline Vertical Flow View (Hidden by default) --}}
<div id="verticalFlowView" class="vertical-flow-view" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Sample Activity Flow</h5>
        <button id="closeFlowViewBtn" class="btn btn-sm btn-secondary">
            <i class="fas fa-times mr-1"></i> Close
        </button>
    </div>
    <div id="verticalFlowContainer" class="vertical-flow-grid">
        <!-- Dynamically populated by JavaScript -->
    </div>
    <hr class="my-4">
</div>
