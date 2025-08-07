// resources/js/text-selection.js
document.addEventListener('alpine:init', () => {
    Alpine.data('textSelectionPopup', () => ({
        isVisible: false,
        positionX: 0,
        positionY: 0,
        selectedText: '',

        init() {
            document.addEventListener('mouseup', this.handleTextSelection.bind(this));
        },

        handleTextSelection(event) {
            const selection = window.getSelection();
            const text = selection.toString().trim();

            if (text.length > 0) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();

                this.selectedText = text;
                this.positionX = rect.left + window.scrollX;
                this.positionY = rect.bottom + window.scrollY + 5;
                this.isVisible = true;
            } else {
                this.close();
            }
        },

        sendToChatGPT() {
            window.open(`https://chatgpt.com/?prompt=${this.selectedText}`, "_blank")
            // Livewire.dispatch('openChatGptWithPrompt', { text: this.selectedText });
            this.close();
        },

        close() {
            this.isVisible = false;
            this.selectedText = '';
        }
    }));
});
