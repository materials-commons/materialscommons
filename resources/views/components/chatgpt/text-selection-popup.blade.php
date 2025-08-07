{{-- resources/views/components/text-selection-popup.blade.php --}}
<div
    x-data="textSelectionPopup"
    x-show="isVisible"
    x-on:mousedown.outside="close"
    x-cloak
    :style="`top: ${positionY}px; left: ${positionX}px;`"
    class="position-fixed text-selection-popup">
    <button
        class="btn btn-success btn-sm"
        x-on:click="sendToChatGPT">
        Ask ChatGPT
    </button>
</div>

@push('scripts')

@endpush
