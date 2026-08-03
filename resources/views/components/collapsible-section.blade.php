@props([
    'title',
    'id',
    'storageKey' => null,
    'open' => false,
    'buttonClass' => 'btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2',
    'titleClass' => 'fw-semibold',
    'titleStyle' => 'font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;',
    'containerClass' => 'mb-3',
    'collapseClass' => 'mb-3',
    'resizePlotly' => false,
])

@php
    $toggleId = "{$id}-toggle";
    $chevronId = "{$id}-chevron";
    $storageKey = $storageKey ?? "mc_collapsible_{$id}_open";
@endphp

<div class="d-flex align-items-center {{ $containerClass }}">
    <button class="{{ $buttonClass }}"
            type="button"
            id="{{ $toggleId }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $id }}"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="{{ $id }}">
        <i class="fas fa-chevron-right fa-fw"
           id="{{ $chevronId }}"
           style="transition: transform 0.2s; font-size:.75rem; {{ $open ? 'transform: rotate(90deg);' : '' }}"></i>
        <span class="{{ $titleClass }}" style="{{ $titleStyle }}">
            {{ $title }}
        </span>
    </button>

    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse {{ $collapseClass }} {{ $open ? 'show' : '' }}" id="{{ $id }}">
    {{ $slot }}
</div>

@push('scripts')
    <script>
        (function () {
            const storageKey = @json($storageKey);
            const panel = document.getElementById(@json($id));
            const chevron = document.getElementById(@json($chevronId));
            const toggle = document.getElementById(@json($toggleId));
            const resizePlotly = @json($resizePlotly);

            if (!panel || !chevron || !toggle) {
                return;
            }

            if (localStorage.getItem(storageKey) === 'true') {
                panel.classList.add('show');
                chevron.style.transform = 'rotate(90deg)';
                toggle.setAttribute('aria-expanded', 'true');
            }

            panel.addEventListener('show.bs.collapse', () => {
                chevron.style.transform = 'rotate(90deg)';
                localStorage.setItem(storageKey, 'true');
            });

            panel.addEventListener('hide.bs.collapse', () => {
                chevron.style.transform = 'rotate(0deg)';
                localStorage.setItem(storageKey, 'false');
            });

            panel.addEventListener('shown.bs.collapse', () => {
                if (resizePlotly && window.Plotly) {
                    panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                }
            });
        })();
    </script>
@endpush
