@props([
    'dataset',
])

@if(!blank($dataset->doi))
    <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="text-muted text-uppercase fw-semibold"
                         style="font-size:.72rem; letter-spacing:.04em;">
                        <i class="fas fa-quote-right me-1"></i>
                        Citation
                    </div>
                    <h5 class="mb-0 mt-1">How to cite this dataset</h5>
                </div>
                <a class="btn btn-outline-secondary btn-sm"
                   data-bs-toggle="modal"
                   href="#cite-dataset-modal">
                    <i class="fas fa-quote-left me-1"></i>
                    Cite dataset
                </a>
            </div>

            <div class="p-3 rounded-3" style="background:#f8fafc; font-size:.86rem; line-height:1.6;">
                <strong>{{ $dataset->name }}</strong>,
                Materials Commons.
                DOI:
                <a href="{{ $dataset->doi }}" class="text-decoration-none">{{ $dataset->doi }}</a>.
            </div>
        </div>
    </section>
@endif
